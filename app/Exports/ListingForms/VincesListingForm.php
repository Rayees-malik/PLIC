<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class VincesListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/vinces.xlsx';

    protected $filename = 'vinces_listingform.xlsx';

    protected $startingRow = 7;

    protected $startingColumn = 'B';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'category',
                'as400Pricing',
                'brand' => function ($query) {
                    return $query->select('id', 'name');
                },
            ])
            ->select(
                'id', 'name', 'stock_id', 'upc', 'size', 'master_units', 'inner_units',
                'purity_sell_by_unit', 'uom_id', 'brand_id', 'category_id'
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $upc = array_pad(str_split(substr($product->upc, 0, 14)), 12, null);
            $data[] = array_merge($upc,
                [
                    $product->brand->name,
                    $product->getName(),
                    null,
                    $product->getSize(),
                    $product->getPrice(),
                    $product->minimumSellBy,
                    null,
                    null,
                    $product->brand->name,
                    null,
                    null,
                    optional($product->category)->name,
                    null,
                    null,
                    optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                ]
            );

            $data[] = [];
            $data[] = [];
        }

        return $data;
    }
}
