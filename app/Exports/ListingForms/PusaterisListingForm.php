<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class PusaterisListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/pusateris.xlsx';

    protected $filename = 'pusateris_listingform.xlsx';

    protected $startingRow = 3;

    protected $startingColumn = 'I';

    protected $priceCode;

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'certifications',
                'countryOrigin',
                'dimensions',
                'uom',
                'subcategory',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
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
                'id', 'name', 'upc', 'master_upc', 'brand_id', 'description',
                'stock_id', 'size', 'uom_id', 'purity_sell_by_unit', 'inner_units', 'master_units',
                'country_origin', 'ingredients', 'category_id',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice(null, $this->priceCode);
            $unitPrice = $product->convertToUnitPrice($price);

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            $data[] = [
                null,
                $product->upc,
                null,
                null,
                $product->master_upc,
                null,
                null,
                substr(strtoupper("{$product->brand->name}-{$product->name}"), 0, 30),
                substr($product->name, 0, 60),
                substr($product->description, 0, 500),
                substr($product->description, 0, 300),
                $product->brand->name,
                null,
                null,
                null,
                $product->stock_id,
                null,
                round($product->size, 2),
                optional($product->uom)->unit,
                $price,
                $product->soldByCase ? 'CASE' : 'EACH',
                $product->caseSize,
                null,
                $unitPrice,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                optional($product->countryOrigin)->name,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $product->ingredients,
                null,
                null,
                null,
                optional($product->category)->name === 'Food & Beverage' ? 60 : 180,
                round(optional($product->dimensions)->height, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->width, 3),
                in_array('Tree Nuts', $doesNotContain) && in_array('Peanuts', $doesNotContain) ? 'Yes' : 'No',
                null,
                in_array('Gluten Free', $certifications) ? 'Yes' : 'No',
                in_array('Organic', $certifications) ? 'Yes' : 'No',
                null,
                in_array('GMO Free', $certifications) ? 'Yes' : 'No',
                null,
                null,
                null,
                null,
                in_array('Dairy', $doesNotContain) ? 'Yes' : 'No',
                in_array('Vegan', $certifications) ? 'Yes' : 'No',
                in_array('Kosher', $certifications) ? 'Yes' : 'No',
            ];
        }

        return $data;
    }
}
