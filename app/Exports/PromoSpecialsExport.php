<?php

namespace App\Exports;

use App\Helpers\PromoHelper;
use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PromoSpecialsExport extends BaseExport
{
    private ?Spreadsheet $spreadsheet = null;

    public function export(Request $request)
    {
        $periodId = $request->get('period_id');

        $request->validate([
            'period_id' => 'required',
        ]);

        if (! $periodId) {
            return redirect()->route('exports.index');
        }

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;

        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;

        $this->spreadsheet = new Spreadsheet;

        // Summary Sheet Set up
        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle('Deals Summary');

        $sheet->setCellValue('D1', $period->name);
        $sheet->getStyle('D1')->getFont()->setBold(true);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('D1:F1');
        if ($basePeriod) {
            $sheet->setCellValue('D1', $basePeriod->name);
            $sheet->setCellValue('G1', $period->name);
            $sheet->getStyle('G1')->getFont()->setBold(true);
            $sheet->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->mergeCells('G1:I1');
        }

        $sheet->fromArray([
            'Brand',
            'Margin',
            'Products',
            'Total Discount',
            'Brand Discount',
            'PL Discount',
            'MCB/OI',
            $basePeriod ? 'Brand Discount' : null,
            $basePeriod ? 'PL Discount' : null,
            $basePeriod ? 'MCB/OI' : null,
        ], null, 'A2');
        $sheet->getStyle('A2:I2')->getFont()->setBold(true);

        foreach (range('A', $basePeriod ? 'I' : 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Products Sheet Set up
        $productSheet = new Worksheet($this->spreadsheet, 'By Product');
        $this->spreadsheet->addSheet($productSheet);

        $productSheet->setCellValue('F1', $period->name);
        $productSheet->getStyle('F1')->getFont()->setBold(true);
        $productSheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $productSheet->mergeCells('F1:H1');
        if ($basePeriod) {
            $productSheet->setCellValue('F1', $basePeriod->name);
            $productSheet->setCellValue('I1', $period->name);
            $productSheet->getStyle('I1')->getFont()->setBold(true);
            $productSheet->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $productSheet->mergeCells('I1:K1');
        }

        $productSheet->fromArray([
            'Brand',
            'Margin',
            'Product',
            'Stock Id',
            'UPC',
            'Total Discount',
            'Brand Discount',
            'PL Discount',
            'MCB/OI',
            $basePeriod ? 'Brand Discount' : null,
            $basePeriod ? 'PL Discount' : null,
            $basePeriod ? 'MCB/OI' : null,
        ], null, 'A2');
        $productSheet->getStyle('A2:J2')->getFont()->setBold(true);

        foreach (range('A', $basePeriod ? 'J' : 'G') as $column) {
            $productSheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Export
        $brands = Product::query()
            ->withPromoPricing([$period, $basePeriod])
            ->whereHas('as400StockData', function ($query) {
                $query->where('status', '!=', 'D');
            })
            ->forExport()
            ->ordered()
            ->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');
        $data = [];
        $productData = [];
        foreach ($brands as $brand => $products) {
            $discounts = PromoHelper::getAllDiscounts($products, $period);
            $brandMargin = $products->first()->brand?->as400Margin?->margin;

            foreach ($discounts as $discount) {
                $data[] = [
                    $brand,
                    $brandMargin,
                    $discount['line_drive'] ? 'Line Drive' : 'Selected SKUs',
                    $discount['total_discount'] ? "{$discount['total_discount']}%" : null,
                    $basePeriod ? ($discount['base_brand_discount'] ? "{$discount['base_brand_discount']}%" : null) : ($discount['brand_discount'] ? "{$discount['brand_discount']}%" : null),
                    $basePeriod ? ($discount['base_pl_discount'] ? "{$discount['base_pl_discount']}%" : null) : ($discount['pl_discount'] ? "{$discount['pl_discount']}%" : null),
                    $basePeriod ? ($discount['base_oi'] ? 'OI' : 'MCB') : ($discount['oi'] ? 'OI' : 'MCB'),
                    $basePeriod ? ($discount['brand_discount'] ? "{$discount['brand_discount']}%" : null) : null,
                    $basePeriod ? ($discount['pl_discount'] ? "{$discount['pl_discount']}%" : null) : null,
                    $basePeriod ? ($discount['oi'] ? 'OI' : 'MCB') : null,
                ];
            }

            foreach ($products as $product) {
                $lineItem = $product->getPromoLineItem($period);
                $baseLineItem = $basePeriod ? $product->getPromoLineItem($basePeriod) : null;

                $totalDiscount = round($basePeriod ? $product->calculateCombinedPromoDiscount($period->id, $basePeriod->id, $startDate) : $product->calculatePromoDiscount($period->id, $startDate));

                if ($totalDiscount == 0) {
                    continue;
                }

                $brandDiscount = round($product->calculatePromoDiscount($period->id, $startDate, true));
                $plDiscount = $lineItem ? round($lineItem->pl_discount ?? 0) : null;
                $baseBrandDiscount = $basePeriod ? round($product->calculatePromoDiscount($basePeriod->id, $startDate, true)) : null;
                $basePLDiscount = $baseLineItem ? round($baseLineItem->pl_discount) : null;

                $productData[] = [
                    $product->brand->name,
                    $brandMargin,
                    $product->getName(),
                    $product->stock_id,
                    $product->sellByUPC,
                    $totalDiscount ? "{$totalDiscount}%" : null,
                    $basePeriod ? ($baseBrandDiscount ? "{$baseBrandDiscount}%" : null) : ($brandDiscount ? "{$brandDiscount}%" : null),
                    $basePeriod ? ($basePLDiscount ? "{$basePLDiscount}%" : null) : ($plDiscount ? "{$plDiscount}%" : null),
                    $basePeriod ? ($baseLineItem ? ($baseLineItem->oi ? 'OI' : 'MCB') : null) : ($lineItem ? ($lineItem->oi ? 'OI' : 'MCB') : null),
                    $basePeriod ? ($brandDiscount ? "{$brandDiscount}%" : null) : null,
                    $basePeriod ? ($plDiscount ? "{$plDiscount}%" : null) : null,
                    $basePeriod ? ($lineItem ? ($lineItem->oi ? 'OI' : 'MCB') : null) : null,
                ];
            }
        }
        $sheet->fromArray($data, null, 'A3');
        $productSheet->fromArray($productData, null, 'A3');
        $this->spreadsheet->setActiveSheetIndex(0);

        return $this->downloadFile($this->spreadsheet, 'promo-specials-' . Str::slug($period->name) . '.xlsx');
    }

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }
}
