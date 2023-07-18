<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class CalgaryCoopListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/calgary_coop.xlsx';

    protected $filename = 'calgary_coop_listingform.xlsx';

    protected $startingRow = 5;

    protected $startingColumn = 'A';

    protected $priceCode = 'CALCOOP';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'regulatoryInfo',
                'certifications',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'stock_id', 'upc', 'brand_id', 'name',
                'size', 'uom_id', 'purity_sell_by_unit', 'inner_units', 'master_units',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            $data[] = [
                $product->name,
                $product->getSize(),
                substr($product->upc, 0, -1),
                substr($product->upc, -1),
                $product->getPrice(),
                $product->getPrice(null, $this->priceCode),
                null,
                null,
                null,
                $product->brand->name,
                null,
                in_array('Dairy', $doesNotContain) ? 'Y' : 'N',
                null,
                in_array('Fair Trade', $certifications) ? 'Y' : 'N',
                in_array('Gluten Free', $certifications) ? 'Y' : 'N',
                in_array('Halal', $certifications) ? 'Y' : 'N',
                null,
                in_array('Kosher', $certifications) ? 'Y' : 'N',
                null,
                null,
                null,
                null,
                in_array('Soy', $doesNotContain) ? 'Y' : 'N',
                null,
                null,
                in_array('GMO Free', $certifications) ? 'Y' : 'N',
                in_array('Tree Nuts', $doesNotContain) || in_array('Peanuts', $doesNotContain) ? 'Y' : 'N',
                in_array('Organic', $certifications) ? 'Y' : 'N',
                null,
                null,
                in_array('Vegan', $certifications) ? 'Y' : 'N',
                in_array('Vegetarian', $certifications) ? 'Y' : 'N',
                null,
            ];
        }

        return $data;
    }
}
