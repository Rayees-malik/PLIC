<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class PommeHabaListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/pomme_haba.xlsx';

    protected $filename = 'pomme_haba_listingform.xlsx';

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
            $price = $product->getPrice();
            $ogd = $product->getOGD($this->priceCode);
            $discount = $ogd['discount'] ? $ogd['discount'] : null;

            $data[] = [
                'Purity Life',
                $product->stock_id,
                $product->upc,
                null,
                $product->brand->name,
                $product->name,
                round($product->size, 2),
                optional($product->uom)->unit,
                $ogd['price'] > 0 ? $ogd['price'] : $price,
                $discount,
                null,
                $product->soldByCase ? 'Y' : 'N',
                $product->caseSize,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
            ];
        }

        return $data;
    }
}
