<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class HealthyPlanetListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/healthy_planet.xlsx';

    protected $filename = 'healthy_planet_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'A';

    protected $priceCode = 'PLANET';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'media',
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
            ->select('id', 'stock_id', 'brand_id', 'name', 'size', 'uom_id', 'upc', 'purity_sell_by_unit', 'master_units', 'inner_units', 'inner_upc', 'master_upc')
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice();
            $ogdPrice = $product->getPrice(priceCode: $this->priceCode);
            $ogd = $product->getOGD($this->priceCode);

            $data[] = [
                'Purity Life',
                $product->stock_id,
                $product->brand->name,
                $product->name,
                $product->getSizeWithUnits(),
                $product->upc,
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                null,
                $product->soldByCase ? round($price / $product->caseSize, 2) : $price,
                $product->soldByCase ? round($ogd['price'] / $product->caseSize, 2) : $ogd['price'],
                $product->soldByCase ? round($ogdPrice / $product->caseSize, 2) : $ogdPrice,
                $product->soldByCase ? $price : null,
                $product->soldByCase ? $ogd['price'] : null,
                $product->soldByCase ? $ogdPrice : null,
                $product->soldByCase ? $product->caseUPC : null,
                $product->soldByCase ? $product->caseSize : null,
                round($price * 1.4, 2),
                round($ogd['discount'], 2),
                null,
                $product->getMedia('product')->count() ? route('products.image', $product->stock_id) : null,
                $product->getMedia('label_flat')->count() ? route('products.labelflat', $product->stock_id) : null,
            ];
        }

        return $data;
    }
}
