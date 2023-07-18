<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class PommeGroceryListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/pomme_grocery.xlsx';

    protected $filename = 'pomme_grocery_listingform.xlsx';

    protected $startingRow = 5;

    protected $startingColumn = 'A';

    protected $priceCode = 'POMME';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
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
                'size', 'uom_id', 'inner_units', 'purity_sell_by_unit'
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

            $data[] = [
                'Purity Life',
                $product->stock_id,
                null,
                $product->upc,
                $product->brand->name,
                $product->name,
                round($product->size, 2),
                optional($product->uom)->unit,
                $price,
                $product->minimumSellBy,
                null,
                null,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
            ];
        }

        return $data;
    }
}
