<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class BlushSpudListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/blush_spud.xlsx';

    protected $filename = 'blush_spud_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'E';

    protected $worksheetIndex = 1;

    protected $priceCode = 'BLUSH';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'innerDimensions',
                'countryOrigin',
                'uom',
                'regulatoryInfo',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', 0);
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name', 'name_fr');
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                // 'as400SpecialPricing' => function ($query) {
                //     $query->byCode($this->priceCode);
                // },
            ])
            ->select(
                'id',
                'upc',
                'brand_id',
                'name',
                'size',
                'uom_id',
                'inner_upc',
                'master_upc',
                'inner_units',
                'master_units',
                'ingredients',
                'description',
                'stock_id',
                'purity_sell_by_unit',
                'cases_per_tie',
                'layers_per_skid',
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
            $unitPrice = $product->convertToUnitPrice($price);

            $certifications = $product->certifications->pluck('name')->toArray();
            $mayContain = $product->allergens->pluck('name')->toArray();
            $doesNotContain = $product->allergens->where('contains', -1)->pluck('name')->toArray();

            $retailerReceives = 1;

            if ($product->soldByCase) {
                $retailerReceives = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
            }

            $data[] = [
                null, // Intro deal approved by brand/vendor/distributor C
                $product->inner_upc == null ? $product->master_upc : $product->inner_upc, // master case upc
                substr($product->upc, 0, -1),
                substr($product->upc, -1),
                null, // UPC entered correctly
                $product->stock_id,
                'Purity Life Health Products LP',
                $product->brand->name,
                $product->name,
                $product->size, // O
                optional($product->uom)->unit,
                $retailerReceives,
                $price,
                null, // unit price
                null, // msrp
                null, // hab map pricing
                null, // SPUD Retail
                null, // msrp gm
                null, // spud gm
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                optional($product->as400Pricing)->taxable ? 'Y' : 'N',
                null, // deposit amount
                null, // recycling fee
                optional($product->countryOrigin)->name,
                in_array('Organic', $certifications) ? 'Y' : 'N',
                in_array('Fair Trade', $certifications) ? 'Y' : 'N',
                null,
                null,
                in_array('Gluten Free', $certifications) ? 'Y' : 'N',
                in_array('Wheat Gluten', $doesNotContain) ? 'Y' : 'N',
                in_array('Tree Nuts', $doesNotContain) || in_array('Peanuts', $doesNotContain) ? 'Y' : 'N',
                null,
                in_array('Vegan', $certifications) ? 'Y' : 'N',
                in_array('Kosher', $certifications) ? 'Y' : 'N',
                in_array('Halal', $certifications) ? 'Y' : 'N',
                in_array('Vegetarian', $certifications) ? 'Y' : 'N',
                in_array('GMO Free', $certifications) ? 'Y' : 'N',
                null,
                in_array('Paleo Friendly', $certifications) ? 'Y' : 'N',
                null, // canadian
                null, // free range
                null, // free run
                null, // plant-based
                $product->ingredients,
                implode(', ', $mayContain),
                $product->description,
                $product->recommended_use || $product->recommended_dosage,
                $product->contraindications,
                optional($product->regulatoryInfo)->serving_size,
                null, // servings per container
                optional($product->regulatoryInfo)->calories ? optional($product->regulatoryInfo)->calories . 'g' : null,
                optional($product->regulatoryInfo)->total_fat ? optional($product->regulatoryInfo)->total_fat . 'g' : null,
                null,
                optional($product->regulatoryInfo)->saturated_fat ? optional($product->regulatoryInfo)->saturated_fat . 'g' : null,
                optional($product->regulatoryInfo)->trans_fat ? optional($product->regulatoryInfo)->trans_fat . 'g' : null,
                null,
                optional($product->regulatoryInfo)->cholesterol ? optional($product->regulatoryInfo)->cholesterol . 'mg' : null,
                optional($product->regulatoryInfo)->sodium ? optional($product->regulatoryInfo)->sodium . 'mg' : null,
                null,
                optional($product->regulatoryInfo)->carbohydrates ? optional($product->regulatoryInfo)->carbohydrates . 'g' : null,
                null,
                optional($product->regulatoryInfo)->fiber ? optional($product->regulatoryInfo)->fiber . 'g' : null,
                null,
                optional($product->regulatoryInfo)->sugar ? optional($product->regulatoryInfo)->sugar . 'g' : null,
                optional($product->regulatoryInfo)->protein ? optional($product->regulatoryInfo)->protein . 'g' : null,
                null,
                null,
                null,
                null, // Iron DV %
                round(optional($product->dimensions)->width, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->height, 3),
                round(optional($product->innerDimensions)->width, 3),
                round(optional($product->innerDimensions)->depth, 3),
                round(optional($product->innerDimensions)->height, 3),
                $product->cases_per_tie ?? null,
                $product->layers_per_skid ?? null,
                round(optional($product->dimensions)->gross_weight, 3),
                round(optional($product->innerDimensions)->gross_weight, 3),
            ];
        }

        return $data;
    }
}
