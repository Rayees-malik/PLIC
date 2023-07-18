<?php

namespace App\Exports;

use App\Helpers\PromoHelper;
use App\Models\Product;
use App\Models\PromoPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CatalogueDealsSummaryExport extends BaseExport
{
    const REPLACE_PAIRS = [
        '[' => '(',
        ']' => ')',
        '*' => '',
        ':' => '',
        '/' => '',
        '\\' => '',
        '?' => '',
    ];

    public function export(Request $request)
    {
        $period1 = $request->period_id1 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id1) : null;
        $period2 = $request->period_id2 ? PromoPeriod::with('basePeriod')->findOrFail($request->period_id2) : null;
        $groceryOnly = $request->grocery_only ?: false;

        $startDate1 = $period1->basePeriod && $period1->basePeriod->start_date < $period1->start_date ? $period1->basePeriod->start_date : $period1->start_date;
        $startDate2 = $period2 ? ($period2->basePeriod && $period2->basePeriod->start_date < $period2->start_date ? $period2->basePeriod->start_date : $period2->start_date) : null;
        $startDate = $startDate2 && $startDate2 < $startDate1 ? $startDate2 : $startDate1;

        $spreadsheet = new Spreadsheet;

        // Summary Sheet Set up
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Deals Summary');

        $summarySheet->setCellValue('A1', $period1->name . ($period2 ? " & {$period2->name}" : ''));
        $summarySheet->getStyle('A1')->getFont()->setBold(true);
        $summarySheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $summarySheet->mergeCells('A1:D1');

        $summarySheet->fromArray([
            'Brand',
            'Products',
            'Discount',
            'Month',
        ], null, 'A2');
        $summarySheet->getStyle('A2:D2')->getFont()->setBold(true);

        foreach (range('A', 'D') as $column) {
            $summarySheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Products Sheet Set up
        $productsSheet1 = $this->getProductsSheet($spreadsheet, $period1);
        $productsSheet2 = $this->getProductsSheet($spreadsheet, $period2);

        // Export
        $brands = Product::withPromoPricing([$period1, $period1->basePeriod, $period2, optional($period2)->basePeriod], true)
            ->forExport()
            ->ordered()
            ->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        $summaryData = [];
        $productData1 = [];
        $productData2 = [];
        foreach ($brands as $brand => $products) {
            $range1 = PromoHelper::getDiscountRange($products, $period1);
            $range2 = $period2 ? PromoHelper::getDiscountRange($products, $period2) : null;

            $sameDeal = $range1 && $range2 && $range1['low'] > 0 && $range1['low'] == $range2['low'] && $range1['high'] == $range2['high']
                && (($range1['line_drive'] && $range2['line_drive']) || ($range1['all_products'] && $range2['all_products']));

            if ($sameDeal || ($range1 && $range1['low'] > 0)) {
                $summaryData[] = [
                    $brand,
                    $range1['line_drive'] ? 'Line Drive' : ($range1['all_products'] ? 'All Products' : 'Selected SKUs'),
                    $range1['low'] == $range1['high'] ? $range1['high'] : "{$range1['low']}-{$range1['high']}",
                    $period1->start_date->shortMonthName . ($sameDeal ? "/{$period2->start_date->shortMonthName}" : ''),
                ];
            } elseif ($range2 && $range2['low'] > 0) {
                $summaryData[] = [
                    $brand,
                    $range2['line_drive'] ? 'Line Drive' : ($range2['all_products'] ? 'All Products' : 'Selected SKUs'),
                    $range2['low'] == $range2['high'] ? $range2['high'] : "{$range2['low']}-{$range2['high']}",
                    $period2->start_date->shortMonthName,
                ];
            }

            foreach ($products as $product) {
                $promoDiscount1 = null;
                $promoPrice1 = $product->calculateCombinedPromoPrice($period1, $period1->basePeriod, $startDate1, false, false, $promoDiscount1);
                $promoDiscount2 = null;
                $promoPrice2 = $product->calculateCombinedPromoPrice($period2, optional($period2)->basePeriod, $startDate2, false, false, $promoDiscount2);

                if ($promoPrice1) {
                    $lineItem = $product->getPromoLineItem($period1);
                    $baseLineItem = $product->getPromoLineItem($period1->basePeriod, true);
                    $productData1[] = $this->getProductValues($product, $promoPrice1, $promoDiscount1, $lineItem, $baseLineItem);
                } else {
                    $productData1[] = $this->getProductValues($product, null, null, null, null);
                }

                if ($promoPrice2) {
                    $lineItem = $product->getPromoLineItem($period2);
                    $baseLineItem = $product->getPromoLineItem($period2->basePeriod, true);
                    $productData2[] = $this->getProductValues($product, $promoPrice2, $promoDiscount2, $lineItem, $baseLineItem);
                } else {
                    $productData2[] = $this->getProductValues($product, null, null, null, null);
                }
            }
        }

        $summarySheet->fromArray($summaryData, null, 'A3');
        $productsSheet1->fromArray($productData1, null, 'A3');
        if ($productsSheet2) {
            $productsSheet2->fromArray($productData2, null, 'A3');
        }
        $spreadsheet->setActiveSheetIndex(0);

        $date = Carbon::now();

        return $this->downloadFile($spreadsheet, "dealssummary_{$date->isoFormat('Y_MM_DD')}.xlsx");
    }

    private function getProductValues($product, $promoPrice, $promoDiscount, $lineItem, $baseLineItem)
    {
        return [
            $product->brand->name,
            $product->getName(),
            $product->stock_id,
            $promoPrice,
            $promoDiscount,
            $lineItem ? round($lineItem->brand_discount) : null,
            $lineItem ? round($lineItem->pl_discount) : null,
            $lineItem && ($lineItem->brand_discount || $lineItem->pl_discount) ? ($lineItem->oi ? 'OI' : 'MCB') : null,
            $baseLineItem ? round($baseLineItem->brand_discount) : null,
            $baseLineItem ? round($baseLineItem->pl_discount) : null,
            $baseLineItem && ($baseLineItem->brand_discount || $baseLineItem->pl_discount) ? ($baseLineItem->oi ? 'OI' : 'MCB') : null,
        ];
    }

    private function getProductsSheet($spreadsheet, $period)
    {
        if (! $period) {
            return;
        }

        $productSheet = new Worksheet($spreadsheet, substr(strtr($period->name, static::REPLACE_PAIRS), 0, 31));
        $spreadsheet->addSheet($productSheet);

        $productSheet->setCellValue('F1', $period->name);
        $productSheet->getStyle('F1')->getFont()->setBold(true);
        $productSheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $productSheet->mergeCells('F1:H1');
        if ($period->basePeriod) {
            $productSheet->setCellValue('I1', $period->basePeriod->name);
            $productSheet->getStyle('I1')->getFont()->setBold(true);
            $productSheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $productSheet->mergeCells('I1:K1');
        }

        $productSheet->fromArray([
            'Brand',
            'Product',
            'Stock Id',
            'Deal Price',
            'Discount',
            'Brand Discount',
            'PL Discount',
            'MCB/OI',
            $period->basePeriod ? 'Brand Discount' : null,
            $period->basePeriod ? 'PL Discount' : null,
            $period->basePeriod ? 'MCB/OI' : null,
        ], null, 'A2');
        $productSheet->getStyle('A1:K2')->getFont()->setBold(true);

        foreach (range('A', $period->basePeriod ? 'J' : 'G') as $column) {
            $productSheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $productSheet;
    }
}
