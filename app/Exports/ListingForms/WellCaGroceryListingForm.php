<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class WellCaGroceryListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/wellca_grocery.xlsx';

    protected $filename = 'well.ca_grocery_listingform.xlsx';

    protected $startingRow = 8;

    protected $priceCode = 'WELL.CA';

    protected $rowCount = null;

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'dimensions',
                'innerDimensions',
                'masterDimensions',
                'uom',
                'certifications',
                'countryOrigin',
                'regulatoryInfo',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
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
                'id', 'brand_id', 'uom_id',
                'name', 'size', 'description', 'benefits', 'recommended_dosage', 'ingredients',
                'purity_sell_by_unit', 'upc', 'stock_id', 'inner_units', 'master_units',
                'inner_upc', 'master_upc', 'country_origin'
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
            $minimumSellBy = $product->minimumSellBy;

            if ($product->inner_units > 1) {
                $caseUPC = $product->inner_upc;
                $caseUnits = $product->inner_units;
                $caseDimensions = $product->innerDimensions;
            } else {
                $caseUPC = $product->master_upc;
                $caseUnits = $product->master_units > 0 ? $product->master_units : 1;
                $caseDimensions = $product->masterDimensions;
            }

            $srp = round(($price * 1.8181) / $minimumSellBy, 2);
            $margin = $srp > 0 ? round(1 - ($price / $srp) / $minimumSellBy, 2) : 0;

            $data[] = [
                $product->brand->name,
                $product->name,
                null,
                $product->getSize(),
                $product->description,
                $product->benefits,
                $product->recommended_dosage,
                $product->ingredients,
                null,
                implode(', ', $product->allergens->pluck('name')->toArray()),
                optional($product->regulatoryInfo)->serving_size,
                optional($product->regulatoryInfo)->calories ? optional($product->regulatoryInfo)->calories . 'g' : null,
                optional($product->regulatoryInfo)->total_fat ? optional($product->regulatoryInfo)->total_fat . 'g' : null,
                null,
                optional($product->regulatoryInfo)->saturated_fat ? optional($product->regulatoryInfo)->saturated_fat . 'g' : null,
                null,
                optional($product->regulatoryInfo)->trans_fat ? optional($product->regulatoryInfo)->trans_fat . 'g' : null,
                null,
                optional($product->regulatoryInfo)->cholesterol ? optional($product->regulatoryInfo)->cholesterol . 'mg' : null,
                null,
                optional($product->regulatoryInfo)->sodium ? optional($product->regulatoryInfo)->sodium . 'mg' : null,
                null,
                optional($product->regulatoryInfo)->carbohydrates ? optional($product->regulatoryInfo)->carbohydrates . 'g' : null,
                null,
                optional($product->regulatoryInfo)->fiber ? optional($product->regulatoryInfo)->fiber . 'g' : null,
                null,
                optional($product->regulatoryInfo)->sugar ? optional($product->regulatoryInfo)->sugar . 'g' : null,
                null,
                optional($product->regulatoryInfo)->protein ? optional($product->regulatoryInfo)->protein . 'g' : null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                $price,
                '$' . $srp,
                $margin,
                optional($product->as400Pricing)->taxable ? 'Fully Taxable' : 'No Tax',
                null,
                $product->upc,
                $product->stock_id,
                $caseUnits,
                $minimumSellBy,
                $product->cases_per_tie,
                (intval($product->cases_per_tie) ?? 1) * (intval($product->layers_per_skid) ?? 1),
                optional($product->dimensions)->width,
                optional($product->dimensions)->depth,
                optional($product->dimensions)->height,
                optional($product->dimensions)->gross_weight,
                optional($product->dimensions)->width,
                optional($product->dimensions)->depth,
                optional($product->dimensions)->height,
                $caseUPC,
                optional($caseDimensions)->gross_weight,
                optional($caseDimensions)->width,
                optional($caseDimensions)->depth,
                optional($caseDimensions)->height,
                optional($product->countryOrigin)->name,
                optional($product->regulatoryInfo)->npn,
            ];
        }

        $this->rowCount = count($data);

        return $data;
    }
}
