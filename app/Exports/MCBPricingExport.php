<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;

class MCBPricingExport extends CSVExport
{
    public function export(Request $request)
    {
        $periodId = $request->get('period_id');
        if (! $periodId) {
            return redirect()->route('exports.index');
        }

        $catCode = $request->get('cat_code');
        $includeDisco = $request->get('include_disco');

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;

        $products = Product::withPromoPricing([$period, $basePeriod], false, $includeDisco, true)->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE);

        $headings = [
            'CompanyName',
            'Product Name',
            'Purity #',
            'UPC Code',
            'Deal Type (OI/MCB)',
            'Cost / Wholesale',
            'Deal',
            'Start Date',
            'End Date',
            $catCode ? 'Cat Cost' : null,
        ];

        $data = [$headings];
        foreach ($products as $product) {
            $lineItem = $product->getPromoLineItem($period, true);
            $baseLineItem = $basePeriod ? $product->getPromoLineItem($basePeriod, true) : null;

            if ($lineItem && $lineItem->oi) {
                $lineItem = null;
            }
            if ($baseLineItem && $baseLineItem->oi) {
                $baseLineItem = null;
            }

            $discoAmount = 0;
            if (! $lineItem) {
                if ($includeDisco) {
                    $discoAmount = optional($product->discoPromo)->brand_discount;
                    if (! $discoAmount) {
                        continue;
                    }
                } else {
                    continue;
                }
            }

            if ($includeDisco && $discoAmount) {
                $brandDiscount = $discoAmount;
                $dollarDiscount = false;
            } else {
                $dollarDiscount = $lineItem->promo->dollar_discount && (! $baseLineItem || $baseLineItem->promo->dollar_discount);

                if ($lineItem->promo->dollar_discount && ! $dollarDiscount) {
                    $brandDiscount = round($product->calculatePromoDiscount($period->id, $period->start_date, true), 2);
                } else {
                    $brandDiscount = $lineItem->brand_discount;
                }

                if ($baseLineItem && $brandDiscount > 0) {
                    if ($baseLineItem->promo->dollar_discount && ! $dollarDiscount) {
                        $brandDiscount += round($product->calculatePromoDiscount($basePeriod->id, $period->start_date, true), 2);
                    } else {
                        $brandDiscount += $baseLineItem->brand_discount;
                    }
                }

                if ($brandDiscount == 0) {
                    continue;
                }
            }

            $data[] = [
                $product->brand->name,
                $product->name,
                $product->stock_id,
                $product->upc,
                'MCB',
                $dollarDiscount ? 'Unit' : 'Wholesale',
                number_format($brandDiscount, $dollarDiscount ? 2 : 0),
                $period->start_date->format('m/d/Y'),
                $period->end_date->format('m/d/Y'),
                $catCode ? $product->as400StockData->category : null,
            ];
        }

        return $this->downloadFile($data, 'mcb_pricing_export.csv');
    }
}
