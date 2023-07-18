<?php

namespace App\Exports\ListingForms;

use App\Exports\BaseExport;
use App\Models\Product;

class NestersListingForm extends BaseExport
{
    protected $template = 'templates/listingforms/nesters.xlsx';

    protected $filename = 'nesters_listingform.xlsx';

    protected $priceCode = 'NESTERS';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name', 'name_fr');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'stock_id', 'brand_id', 'name', 'size', 'uom_id', 'upc',
                'inner_upc', 'shelf_life', 'shelf_life_units', 'purity_sell_by_unit',
                'inner_units', 'master_units',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function export($stockIds, $includeNonCatalogue)
    {
        $spreadsheet = property_exists($this, 'template') ? $this->loadFile($this->template) : abort(404);

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $index => $product) {
            $price = $product->getPrice();
            $staltPrice = $product->getPrice(null, $this->priceCode);
            $taxable = optional($product->as400Pricing)->taxable ? 'Y' : 'N';
            $shelfLife = $product->shelf_life_units == 'years' ? $product->shelf_life * 365 : $product->shelf_life * 30;

            $offInvoice = $price && $staltPrice && $price > $staltPrice ? $price - $staltPrice : null;

            // Clone template sheet
            $clone = clone $spreadsheet->getActiveSheet();
            $clone->setTitle(strval($product->stock_id));
            $sheet = $spreadsheet->addSheet($clone, $index + 1);

            // Set active sheet so that I can use setCellValue for better performance than getCell() -> setValue()
            $spreadsheet->setActiveSheetIndex($index + 1);

            $spreadsheet->getActiveSheet()->setCellValue('BT5', date('F d, Y'));
            $spreadsheet->getActiveSheet()->setCellValue('C10', $product->brand->name);
            $spreadsheet->getActiveSheet()->setCellValue('R10', $product->name);
            $spreadsheet->getActiveSheet()->setCellValue('BE10', $product->minimumSellBy);
            $spreadsheet->getActiveSheet()->setCellValue('BI10', $product->caseSize);
            $spreadsheet->getActiveSheet()->setCellValue('BL10', $product->size);
            $spreadsheet->getActiveSheet()->setCellValue('BP10', $product->uom->unit);
            $this->insertUPC($spreadsheet, $product->upc, 13);
            $this->insertUPC($spreadsheet, $product->inner_upc, 15);
            $this->insertUPC($spreadsheet, $product->master_upc, 17);
            $spreadsheet->getActiveSheet()->setCellValue('C22', $taxable);
            $spreadsheet->getActiveSheet()->setCellValue('G22', $taxable);
            $spreadsheet->getActiveSheet()->setCellValue('K22', $taxable);
            $spreadsheet->getActiveSheet()->setCellValue('BG20', $price);
            $spreadsheet->getActiveSheet()->setCellValue('BG21', $price);
            $spreadsheet->getActiveSheet()->setCellValue('BG22', $offInvoice);
            $spreadsheet->getActiveSheet()->setCellValue('W26', $product->stock_id);
            $spreadsheet->getActiveSheet()->setCellValue('BB31', $product->shelf_life ? 'Y' : 'N');
            $spreadsheet->getActiveSheet()->setCellValue('BB32', $product->shelf_life ? $shelfLife : null);
        }

        // Remove the template worksheet
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('NPPF'));
        $spreadsheet->removeSheetByIndex($sheetIndex);

        // Set the first product worksheet as active sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Download the file
        $this->downloadFile($spreadsheet, $this->filename);
    }

    protected function insertUPC($spreadsheet, $upc, $row)
    {
        $upcArray = array_pad(str_split($upc), -12, null);
        array_splice($upcArray, 1, 0, [null]);

        if (count($upcArray) == 13) {
            array_splice($upcArray, 7, 0, [null]);
            array_splice($upcArray, 13, 0, [null]);
        } elseif (count($upcArray) == 14) {
            array_splice($upcArray, 7, 0, [null]);
        }

        $spreadsheet->getActiveSheet()->fromArray($upcArray, null, "F{$row}");
    }
}
