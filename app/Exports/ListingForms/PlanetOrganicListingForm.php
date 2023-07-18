<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class PlanetOrganicListingForm extends SimpleListingForm
{
    // For some reason this template does not work as .xlsx
    protected $template = 'templates/listingforms/planet_organic.xls';

    protected $filename = 'planet_organic_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'B';

    protected $priceCode = 'PLANET';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'countryOrigin',
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
                'id', 'name', 'brand_id', 'uom_id', 'upc', 'country_origin',
                'ingredients', 'shelf_life', 'stock_id', 'size',
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
                $product->upc,
                null,
                $product->brand->name,
                $product->name,
                $product->getSize(),
                null,
                $price,
                $product->minimumSellBy,
                'EA',
                null,
                null,
                'Purity Life',
                $product->stock_id,
                optional($product->as400Pricing)->taxable ? 'Taxable' : '1 - NO TAX',
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                optional($product->countryOrigin)->name === 'Canada' ? 'YES' : 'NO',
                $product->ingredients,
                $product->shelf_life,
            ];
        }

        return $data;
    }
}
