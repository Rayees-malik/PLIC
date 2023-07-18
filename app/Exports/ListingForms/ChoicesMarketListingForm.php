<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class ChoicesMarketListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/choices_market.xlsx';

    protected $filename = 'choices_market_listingform.xlsx';

    protected $startingRow = 5;

    protected $startingColumn = 'B';

    protected $priceCode = 'CHOICES';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'countryOrigin' => function ($query) {
                    $query->select('id', 'name');
                },
                'uom' => function ($query) {
                    $query->select('id', 'unit');
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name', 'made_in_canada');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'taxable', 'wholesale_price');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
            ])
            ->select(
                'id', 'name', 'stock_id', 'upc', 'size', 'inner_units',
                'uom_id', 'brand_id', 'description', 'country_origin',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice();
            $edlpPrice = $product->getPrice(null, $this->priceCode);
            $discount = $price != 0 ? round(1 - ($edlpPrice / $price), 2) : 0;

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            $data[] = [
                $product->upc,
                null,
                null,
                $product->stock_id,
                null,
                null,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Yes' : 'No',
                optional($product->as400Pricing)->taxable ? 'Yes' : 'No',
                null,
                $product->name,
                null,
                round($product->size, 2),
                optional($product->uom)->unit,
                $product->caseSize,
                $price,
                $discount,
                null,
                null, //$edlpPrice,
                null,
                null,
                null, null, null, null,
                $product->countryOrigin?->name,
                null, null, null, null,
                null, null, null, null, null,
                null, null, null, null, null,
                null, null, null, null, null,
                $product->description,
                in_array('Gluten Free', $certifications) ? 'Y' : null,
                in_array('Dairy', $doesNotContain) ? 'Y' : null,
                in_array('Vegan', $certifications) ? 'Y' : null,
                null,
                null,
                in_array('Peanuts', $doesNotContain) && in_array('Tree Nuts', $doesNotContain) ? 'Y' : null,
                in_array('Fair Trade', $certifications) ? 'Y' : null,
                in_array('Organic', $certifications) ? 'Y' : null,
                null,
                in_array('Wheat Gluten', $doesNotContain) ? 'Y' : null,
                in_array('GMO Free', $certifications) ? 'Y' : null,
                in_array('Halal', $certifications) ? 'Y' : null,
                null,
                null,
                null,
                null,
                null,
                in_array('Vegetarian', $certifications) ? 'Y' : null,
                null,
                in_array('Peanuts', $doesNotContain) ? 'Y' : null,
                in_array('Tree Nuts', $doesNotContain) ? 'Y' : null,
                in_array('Soy', $doesNotContain) ? 'Y' : null,
                $product->brand->made_in_canada ? 'Y' : 'N',
            ];
        }

        return $data;
    }
}
