<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomPricingExport extends CSVExport
{
    public function export(Request $request)
    {
        $periodId = $request->get('period_id');
        if (! $periodId) {
            return redirect()->route('exports.index');
        }

        $period = PromoPeriod::with('basePeriod')->findOrFail($periodId);
        $basePeriod = $period->basePeriod;
        $startDate = $basePeriod && $basePeriod->start_date < $period->start_date ? $basePeriod->start_date : $period->start_date;

        $includeCosts = $request->include_costs ?: false;
        $includeNonCatalogue = $request->include_noncatalogue == 1;

        $products = Product::forExport()
            ->withPromoPricing([$period, $basePeriod], true, false, $includeNonCatalogue)
            ->with([
                'uom',
                'futureLandedCosts',
                'brand' => function ($query) {
                    $query->with(['currency' => function ($query) {
                        $query->select('id', 'exchange_rate');
                    }])->select('id', 'name', 'business_partner_program', 'category_code', 'currency_id');
                },
                'catalogueCategory' => function ($query) {
                    $query->select('id', 'name');
                },
                'subcategory' => function ($query) {
                    $query->select('id', 'name', 'grocery');
                },
            ])->ordered()->get()->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE);

        $data[] = [
            'Category Code', 'Brand Name', 'Product Stock ID', 'Product Name', 'Product Size',
            'Product Name Size', 'Product Name (French)', 'Product Size (French)', 'Product Name Size (French)',
            'Case Size', 'PuritySellByUPC', 'RetailerSellByUPC', 'ProductSoldByCase', 'IsDisplay',
            'PurityWholesale', 'RetailerIndividualCost', 'ProductInnerUnits', 'Status', 'Landed Cost',
            'Promo Discount', 'Promo Month', 'Promo Price', 'Bilingual', 'IsGrocery', 'MonthDeal',
            'ShowDeal', 'Retailer Unit Qty Received', 'Retailer Order By Case Qty', 'Brand Stock ID',
        ];

        foreach ($products as $product) {
            $promoDiscount = null;
            $promoPrice = $product->calculatePromoPrice($period, $startDate, false, false, $promoDiscount);
            $promoDiscount = round($promoDiscount);

            $retailerReceives = 1;
            $retailerOrderBy = 1;
            if ($product->soldByCase) {
                $retailerReceives = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
            } else {
                $retailerOrderBy = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
            }

            $hasFrench = ! empty($product->name_fr);
            $data[] = [
                optional($product->as400StockData)->category_code ?? $product->brand->category_code,
                $product->brand->name,
                $product->stock_id,
                $product->getName(),
                $product->getSizeWithUnits(),
                "{$product->name} {$product->getSizeWithUnits()}",
                $hasFrench ? $product->name_fr : null,
                $hasFrench ? $product->getSizeWithUnitsFR() : null,
                $hasFrench ? "{$product->name_fr} {$product->getSizeWithUnitsFR()}" : null,
                $product->master_units,
                $product->sellByUPC,
                $product->upc,
                $product->soldByCase ? 'Y' : 'N',
                $product->is_display ? 'Y' : 'N',
                $product->getPrice($startDate),
                $product->getUnitPrice($startDate),
                $product->inner_units < 1 ? 1 : $product->inner_units,
                $product->as400StockData->status,
                $includeCosts ? $product->getLandedCost($startDate) : null,
                $promoDiscount ? "{$promoDiscount}%" : null,
                $promoDiscount ? $period->name : null,
                $promoDiscount ? $promoPrice : null,
                $product->packaging_language,
                optional($product->subcategory)->grocery ? 'Y' : 'N',
                $promoDiscount ? "{$product->calculatePromoDiscount($period, $startDate)}%" : null,
                $promoDiscount && $basePeriod ? "{$product->calculatePromoDiscount($basePeriod, $startDate)}%" : null,
                $retailerReceives,
                $retailerOrderBy,
                $product->brand_stock_id,
            ];
        }

        if (! $includeCosts) {
            $index = array_search('Landed Cost', $data[0]);

            $j = count($data);
            for ($i = 0; $i < $j; $i++) {
                unset($data[$i][$index]);
            }
        }

        return $this->downloadFile($data, 'custom-pricing-' . Str::slug($period->name) . '.csv');
    }
}
