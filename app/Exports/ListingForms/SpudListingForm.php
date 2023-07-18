<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class SpudListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/spud.xlsx';

    protected $filename = 'spud_listingform.xlsx';

    protected $startingRow = 3;

    protected $startingColumn = 'B';

    protected $worksheetIndex = 1;

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'countryOrigin',
                'allergens',
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->forDate();
                    }])->select('id', 'name', 'name_fr');
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'regulatoryInfo' => function ($query) {
                    $query->select('product_id', 'npn');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing',
            ])
            ->select(
                'id', 'upc', 'stock_id', 'brand_id', 'name', 'uom_id',
                'size', 'inner_units', 'country_origin', 'ingredients',
                'description', 'recommended_use', 'contraindications',
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

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->where('contains', -1)->pluck('name')->toArray();
            $mayContain = implode(', ', $product->allergens->whereIn('contains', [0, 1])->pluck('name')->toArray());

            $data[] = [
                $product->upc,
                $product->stock_id,
                optional($product->regulatoryInfo)->npn,
                $product->brand->name,
                $product->name,
                $product->getSize(),
                $product->minimumSellBy,
                $price,
                null,
                null,
                null,
                null,
                null,
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                optional($product->countryOrigin)->name,
                in_array('Organic', $certifications) ? 'Y' : 'N',
                in_array('Fair Trade', $certifications) ? 'Y' : 'N',
                null,
                null,
                in_array('Gluten Free', $certifications) ? 'Y' : 'N',
                in_array('Tree Nuts', $doesNotContain) || in_array('Peanuts', $doesNotContain) ? 'Y' : 'N',
                in_array('Dairy', $doesNotContain) ? 'Y' : 'N',
                in_array('Vegan', $certifications) ? 'Y' : 'N',
                in_array('Vegetarian', $certifications) ? 'Y' : 'N',
                in_array('GMO Free', $certifications) ? 'Y' : 'N',
                null,
                in_array('Paleo Friendly', $certifications) ? 'Y' : 'N',
                null,
                null,
                null,
                $product->ingredients,
                $mayContain,
                $product->description,
                $product->recommended_use,
                $product->contraindications,
                'Purity Life',
            ];
        }

        return $data;
    }
}
