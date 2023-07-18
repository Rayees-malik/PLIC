<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class NaturesFareListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/natures_fare.xlsx';

    protected $filename = 'natures_fare_listingform.xlsx';

    protected $startingRow = 7;

    protected $startingColumn = 'A';

    protected $priceCode = 'FARE';

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
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id', 'name', 'upc', 'stock_id', 'size', 'uom_id', 'brand_id', 'minimum_order_units',
                'purity_sell_by_unit', 'inner_units', 'master_units',
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
            $certifications = $product->certifications->pluck('name')->toArray();

            $data[] = [
                $product->upc,
                $product->stock_id,
                $product->brand->name,
                $product->name,
                $product->getSize(),
                $price,
                $product->caseSize,
                null,
                null,
                $product->minimumSellBy,
                in_array('Gluten Free', $certifications) ? 'Y' : null,
                in_array('GMO Free', $certifications) ? 'Y' : null,
                in_array('Organic', $certifications) ? 'Y' : null,
                null,
                $product->brand->broker, // Placeholder for now
            ];
        }

        return $data;
    }
}
