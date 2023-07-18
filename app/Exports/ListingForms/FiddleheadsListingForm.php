<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class FiddleheadsListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/fiddleheads.xlsx';

    protected $filename = 'fiddleheads_listingform.xlsx';

    protected $startingRow = 2;

    protected $startingColumn = 'B';

    protected $priceCode = 'FIDDLE';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
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
            ->select('id', 'upc', 'stock_id', 'brand_id', 'name')
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice(null, $this->priceCode);

            $data[] = [
                $product->upc,
                $product->stock_id,
                $product->brand->name,
                $product->name,
                null,
                null,
                $price,
                $price,
                null,
                null,
                null,
                null,
                null,
                'Purity Life Health Products LP',
            ];
        }

        return $data;
    }
}
