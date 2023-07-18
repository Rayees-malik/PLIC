<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class GoodnessMeListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/goodness_me.xlsx';

    protected $filename = 'goodness_me_listingform.xlsx';

    protected $startingRow = 3;

    protected $startingColumn = 'A';

    protected $worksheetIndex = 3;

    protected $priceCode = 'GOODME';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'allergens',
                'countryOrigin',
                'regulatoryInfo',
                'uom',
                'dimensions',
                'innerDimensions',
                'masterDimensions',
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select([
                'id', 'name', 'stock_id', 'brand_id', 'country_origin', 'size',
                'uom_id', 'upc', 'inner_upc', 'master_upc', 'inner_units', 'master_units',
                'cases_per_tie', 'layers_per_skid', 'shelf_life', 'purity_sell_by_unit',
            ])
            ->whereIn('stock_id', $stockIds)
            ->orderBy('stock_id')
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);

        foreach ($products as $product) {
            $price = $product->getPrice();
            $ogdPrice = $product->getPrice(null, $this->priceCode);
            $ogd = $product->getOGD($this->priceCode)['discount'];
            $discount = $ogd ? $ogd * 100 . '%' : null;

            $allergens = $product->certifications()->pluck('name')->join(', ');

            $shelfLife = $product->shelf_life_units == 'years' ? $product->shelf_life * 365 : $product->shelf_life * 30;

            $data[] = [
                'Purity Life Health Products LP',
                $product->brand->name,
                $product->name,
                $product->brand->name,
                optional($product->regulatoryInfo)?->npn,
                optional($product->countryOrigin)->name,
                $product->getSize(),
                $product->upc,
                $product->inner_upc,
                $product->master_upc,
                $product->stock_id,
                $product->inner_units ?? 1,
                $product->master_units ?? 1,
                'No',
                round(optional($product->dimensions)->width, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->height, 3),
                round(optional($product->innerDimensions)->width, 3),
                round(optional($product->innerDimensions)->depth, 3),
                round(optional($product->innerDimensions)->height, 3),
                round(optional($product->masterDimensions)->width, 3),
                round(optional($product->masterDimensions)->depth, 3),
                round(optional($product->masterDimensions)->height, 3),
                $product->cases_per_tie ?? null,
                $product->layers_per_skid ?? null,
                optional($product->dimensions)->gross_weight ?? null,
                optional($product->innerDimensions)->gross_weight ?? null,
                optional($product->masterDimensions)->gross_weight ?? null,
                $shelfLife,
                null,
                null,
                null,
                null,
                $this->resolveSoldByCase($product),
                1,
                null,
                null,
                null,
                null,
                $price,
                $discount,
                null,
                null,
                null,
                $ogdPrice,
                null,
                optional($product->as400Pricing)->taxable ? '13%' : null,
                null,
                null,
                null,
                null,
                $allergens,
            ];
        }

        return $data;
    }

    private function resolveSoldByCase($product)
    {
        if (! $product->soldByCase) {
            return 'Unit';
        }

        if ($product->inner_units <= 1) {
            return 'Case';
        }

        if ($product->inner_units > 1) {
            return 'Ipack';
        }
    }
}
