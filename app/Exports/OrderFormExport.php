<?php

namespace App\Exports;

use App\Helpers\ExcelHelper;
use App\Models\Brand;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class OrderFormExport extends BaseExport
{
    const ENGLISH_FOOTER = "Order online or Email to: order@puritylife.com\nPrice and availability subject to change";

    const FRENCH_FOOTER = "Commandez en ligne ou écrivez un courriel à: order@puritylife.com\nLes prix sont sujets à changement sans préavis";

    public function export(Request $request, $downloadFile = true, bool $asBulkExport = false)
    {
        $stockIds = array_filter(explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->get('stock_ids')))));

        $groceryOnly = $request->get('grocery_only') == 1;
        $onDealOnly = $request->get('ondeal_only') == 1;
        $listedAfter = $request->get('listed_after');

        $periodId1 = $request->get('period_id1');
        $period1 = $periodId1 ? PromoPeriod::with('basePeriod')->find($periodId1) : null;
        $basePeriod1 = $period1 ? $period1->basePeriod : null;

        $periodId2 = $request->get('period_id2');
        $period2 = $periodId2 ? PromoPeriod::with('basePeriod')->find($periodId2) : null;
        $basePeriod2 = $period2 ? $period2->basePeriod : null;

        $startDate1 = $period1 ? ($period1->basePeriod && $period1->basePeriod->start_date < $period1->start_date ? $period1->basePeriod->start_date : $period1->start_date) : null;
        $startDate2 = $period2 ? ($period2->basePeriod && $period2->basePeriod->start_date < $period2->start_date ? $period2->basePeriod->start_date : $period2->start_date) : null;
        $startDate = $startDate2 && (! $startDate1 || $startDate2 < $startDate1) ? $startDate2 : $startDate1;

        if ($period2 && ! $period1) {
            $period1 = $period2;
            $basePeriod1 = $basePeriod2;

            $period2 = null;
            $basePeriod2 = null;
        }

        $brandIds = Arr::wrap($request->get('brand_id'));
        $allBrands = ! $stockIds && ! $brandIds;

        if ($stockIds) {
            $brands = Brand::whereHas('products', function ($query) use ($stockIds) {
                $query->whereIn('stock_id', $stockIds);
            })->select('id', 'name')->get();
        } else {
            // if (! $brandIds && $allBrands && ! ($groceryOnly || $listedAfter || ($onDealOnly && $period1))) {
            //     flash('All Brands only works with Grocery Only, On Deal Only, or Listed After', 'danger');

            //     return redirect()->route('exports.index');
            // }

            if ($allBrands) {
                $brands = collect([1, 1]); // need count > 1
            } else {
                $brands = Brand::select('id', 'name')->findMany($brandIds);
            }
        }

        if (! $allBrands && ! $brands->count()) {
            flash('No matching products could be found.', 'danger');

            return redirect()->route('exports.index');
        }

        $english = $request->get('language') == 'F' ? false : true;
        $caseUPC = $request->get('upc') > 1;
        $upc = $request->get('upc') > 0;
        $includeNonCatalogue = $request->get('include_noncatalogue') == 1;

        $spreadsheet = $this->loadFile('templates/orderform' . ($english ? '' : '_fr') . '.xlsx');
        $sheet = $spreadsheet->getActiveSheet();

        // Remove columns we are not using in this export
        $columns = 11;
        if (! $period2) {
            $sheet->removeColumn('K');
            $sheet->removeColumn('J');
            $columns -= 2;
        }
        if (! $period1) {
            $sheet->removeColumn('I');
            $sheet->removeColumn('H');
            $columns -= 2;
        }
        if (! $caseUPC) {
            $sheet->removeColumn('D');
            $columns -= 1;
        }
        if (! $upc) {
            $sheet->removeColumn('C');
            $columns -= 1;
        }

        // Calculate the form's title
        $formTitle = ($brands->count() > 1 ? ($english ? 'Multiple Brands' : 'Marques Mutiples') : $brands->first()->name);
        if ($period1) {
            $formTitle .= ' - ' . (empty($period1->order_form_header) ? $period1->name : $period1->order_form_header);
            if ($period2) {
                $formTitle .= ' and ' . (empty($period2->order_form_header) ? $period2->name : $period2->order_form_header);
            }
        } else {
            $formTitle .= $english ? ' - Order Form' : ' - Bon de Commande';
        }
        $sheet->setCellValue('A1', $formTitle);

        $productBrands = Product::withPromoPricing([$period1, $basePeriod1, $period2, $basePeriod2], ! $onDealOnly, true, $includeNonCatalogue)
            ->with(['catalogueCategory:id,name,name_fr,sort', 'uom:id,unit'])
            ->select(
                'id',
                'name',
                'name_fr',
                'packaging_language',
                'brand_id',
                'description',
                'description_fr',
                'upc',
                'stock_id',
                'inner_upc',
                'master_upc',
                'size',
                'catalogue_category_id',
                'inner_units',
                'master_units',
                'uom_id',
                'purity_sell_by_unit',
                'is_display',
            );

        if ($stockIds) {
            $productBrands->whereIn('stock_id', $stockIds);
        } elseif ($brandIds) {
            $productBrands->whereIn('brand_id', $brandIds);
        }

        if ($groceryOnly) {
            $productBrands->whereHas('subcategory', function ($query) {
                $query->where('grocery', true);
            });
        }
        if ($listedAfter) {
            $productBrands->where('listed_on', '>=', $listedAfter);
        }

        $productBrands = $productBrands
            ->ordered()
            ->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        if (! $productBrands->count()) {
            // if ($downloadFile) {
            //     return false;
            // }

            if ($asBulkExport) {
                return false;
            }

            flash('No matching products could be found.', 'danger');

            return redirect()->route('exports.index');
        }

        $row = 11;
        $highestColumn = ExcelHelper::indexToColumn($columns);

        foreach ($productBrands as $brand => $allProducts) {
            if ($productBrands->count() > 1) {
                $sheet->setCellValueByColumnAndRow(1, $row, strtoupper($brand));

                // styling
                $sheet->mergeCells("A{$row}:{$highestColumn}{$row}");
                $sheet->getStyle("A{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('C4D7F0');
            }

            $row++;

            $categories = $allProducts->sortBy('catalogueCategory.sort')->groupBy('catalogueCategory.name');

            foreach ($categories as $category => $products) {
                $categoryName = $english ? $category : $products->first()->catalogueCategory->name_fr;

                $sheet->setCellValueByColumnAndRow(1, $row, $categoryName);

                // styling
                $sheet->mergeCells("A{$row}:{$highestColumn}{$row}");

                $row++;

                $products = $products->sortBy('size')->sortBy('name');

                foreach ($products as $product) {
                    $productData = [null, $product->stock_id];

                    if ($upc) {
                        $productData[] = $product->upc;
                    }

                    if ($caseUPC) {
                        $productData[] = $product->caseUPC;
                    }

                    $productData[] = $english ? $product->getName() : $product->getNameFR();
                    $productData[] = $english ? $product->getSizeWithUnits() : $product->getSizeWithUnitsFR();
                    $productData[] = $product->getPrice($period1 ? $startDate : null);

                    if ($period1) {
                        $discountPrice = null;
                        $discount = $product->calculateCombinedPromoDiscount($period1, $basePeriod1, $startDate, false, true, $discountPrice);
                        $productData[] = $discount ? round($discount, 0) . '%' : '';
                        $productData[] = $discountPrice;
                    }
                    if ($period2) {
                        $discountPrice = null;
                        $discount = $product->calculateCombinedPromoDiscount($period2, $basePeriod2, $startDate, false, true, $discountPrice);
                        $productData[] = $discount ? round($discount, 0) . '%' : '';
                        $productData[] = $discountPrice;
                    }

                    $sheet->fromArray($productData, null, 'A' . $row);

                    if ($product->is_display && (($english && $product->description) || (! $english && $product->description_fr))) {
                        $row++;

                        $descriptionRow = [null, null];

                        if ($upc) {
                            $descriptionRow[] = null;
                        }

                        if ($caseUPC) {
                            $descriptionRow[] = null;
                        }

                        $descriptionRow[] = $english ? $product->description : $product->description_fr;

                        $sheet->fromArray($descriptionRow, null, 'A' . $row);

                        // styling
                        $descriptionCol = ExcelHelper::indexToColumn(2 + ($upc ? 1 : 0) + ($caseUPC ? 1 : 0));

                        $sheet->getStyle("{$descriptionCol}{$row}:{$descriptionCol}{$row}")->applyFromArray([
                            'alignment' => [
                                'wrapText' => true,
                            ], 'font' => [
                                'italic' => true,
                            ],
                        ]);

                        $sheet->getRowDimension($row)->setRowHeight(-1);
                    }

                    $row++;
                }
            }
        }

        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle("A11:{$highestColumn}{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
        ]);

        $highestRow += 2;
        $sheet->setCellValue("A{$highestRow}", $english ? static::ENGLISH_FOOTER : static::FRENCH_FOOTER);
        $sheet->getStyle("A{$highestRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$highestRow}")->getFont()->getColor()->setARGB('FFFFFF');
        $sheet->getStyle("A{$highestRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('1B3E6F');
        $sheet->getStyle("A{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A{$highestRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A{$highestRow}")->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($highestRow)->setRowHeight(50);
        $sheet->mergeCells("A{$highestRow}:{$highestColumn}{$highestRow}");

        // Move header image to account for column removals
        $header = $sheet->getDrawingCollection()[0];
        $header->setResizeProportional(false);
        $header->setEditAs('absolute');
        $header->setCoordinates('A2');

        if (! $upc && $period1 && $period2) {
            // no UPCs UPC and both periods
            $header->setOffsetX(99);
        } elseif ((! $upc && $period1) || (! $upc && $period2)) {
            // no UPCs but at least one period
            $header->setOffsetX(31);
        } elseif ((! $upc && ! $period1 && ! $period2)) {
            // no UPC and no periods
            $header->setOffsetX(0);
        } elseif (($upc && $period1 && $period2)) {
            // product UPC and both periods
            $header->setOffsetX(60);
        } elseif (($upc && $period1) || ($upc && $period2)) {
            // product UPC and at least one period
            $header->setOffsetX(32);
        } elseif ($upc && ! $period1 && ! $period2) {
            // product UPC and no periods
            $header->setOffsetX(0);
        } elseif (($caseUPC && $period1 && $period2)) {
            // both UPCs and both periods
            $header->setOffsetX(23);
        } elseif (($caseUPC && $period1) || ($caseUPC && $period2)) {
            // both UPCs and at least one period
            $header->setOffsetX(11);
        } elseif ($caseUPC && ! $period1 && ! $period2) {
            // both UPCs  and no periods
            $header->setOffsetX(38);
        }

        // Set default selected cell & print area
        $sheet->getPageSetup()->setPrintArea("A:{$highestColumn}");
        $sheet->setSelectedCell('C4');

        // Download file
        if ($downloadFile) {
            return $this->downloadFile($spreadsheet, Str::slug($formTitle) . '.xlsx');
        } else {
            $tempFile = tempnam(sys_get_temp_dir(), 'plic_');
            $this->writeFile($spreadsheet, $tempFile);

            return $tempFile;
        }
    }
}
