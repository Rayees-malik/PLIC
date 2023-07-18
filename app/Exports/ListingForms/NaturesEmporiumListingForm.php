<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class NaturesEmporiumListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/natures_emporium.xlsx';

    protected $filename = 'natures_emporium_listingform.xlsx';

    protected $startingRow = 7;

    protected $priceCode = 'NATEMP';

    protected $margin = 1.18;

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'uom',
                'category',
                'subcategory',
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id',
                'stock_id',
                'subcategory_id',
                'category_id',
                'upc',
                'brand_id',
                'name',
                'size',
                'uom_id',
                'purity_sell_by_unit',
                'inner_units',
                'master_units',
                'landed_cost',
                'master_upc',
                'inner_upc'
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $price = $product->getPrice(priceCode: $this->priceCode);

            $ogdPrice = $product->getPrice(null, $this->priceCode);
            $ogd = $product->getOGD($this->priceCode)['discount'];
            $discount = $ogd ? $ogd * 100 . '%' : null;

            $unitPrice = $product->convertToUnitPrice($price);

            $certifications = $product->certifications->pluck('name')->toArray();

            $data[] = [
                optional($product->category)->name,
                optional($product->subcategory)->category,
                $product->brand->name,
                $product->name,
                $product->caseUPC,
                $product->upc,
                $product->stock_id,
                round($product->size, 2),
                optional($product->uom)->unit,
                $product->sold_by_case ? round(($product->landed_cost * $this->margin) / $product->caseSize, 2) : round(($product->landed_cost * $this->margin), 2), //$unitPrice,
                $discount, // Every Day Discount
                $product->caseSize,
                round($product->landed_cost * $this->margin - ($product->landed_cost * $this->margin * $ogd), 2),
                null, // SRP
                $product?->as400Pricing?->taxable ? 'Y' : 'N',
                in_array('Organic', $certifications) ? 'Y' : 'N',
                in_array('GMO Free', $certifications) ? 'Y' : 'N',
                in_array('Fair Trade', $certifications) ? 'Y' : 'N',
                in_array('Gluten Free', $certifications) ? 'Y' : 'N',
                null,
                null,
                null,
                null,
                null,
            ];
        }

        return $data;
    }
}
