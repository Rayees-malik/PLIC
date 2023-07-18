<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class BuyWellListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/buy_well.xlsx';

    protected $filename = 'buy_well_listingform.xlsx';

    protected $startingRow = 7;

    protected $startingColumn = 'A';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'countryOrigin',
                'dimensions',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', '>', -1);
                },
                'brand' => function ($query) {
                    return $query->select('id', 'name');
                },
                'regulatoryInfo' => function ($query) {
                    $query->select('product_id', 'npn');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
            ])
            ->select(
                'id', 'name', 'brand_id', 'description', 'benefits',
                'recommended_dosage', 'ingredients', 'upc', 'stock_id',
                'purity_sell_by_unit', 'inner_units', 'size', 'uom_id',
                'country_origin',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];
        $products = $this->query($stockIds, $includeNonCatalogue);

        foreach ($products as $product) {
            $data[] = [
                $product->brand->name,
                $product->name,
                null,
                null,
                $product->description,
                $product->benefits,
                $product->recommended_dosage,
                $product->ingredients,
                $product->allergens->count() ? implode(', ', $product->allergens->pluck('name')->toArray()) : '',
                null,
                $product->certifications->count() ? implode(', ', $product->certifications->pluck('name')->toArray()) : '',
                $product->getPrice(),
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Fully Taxable' : 'Not Taxable',
                null,
                $product->upc,
                $product->stock_id,
                $product->minimumSellBy,
                $product->soldByCase ? 'Yes' : 'No',
                round(optional($product->dimensions)->width, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->height, 3),
                round(optional($product->dimensions)->gross_weight, 3),
                round(optional($product->dimensions)->width, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->height, 3),
                optional($product->countryOrigin)->name,
                optional($product->regulatoryInfo)->npn,
            ];
        }

        return $data;
    }
}
