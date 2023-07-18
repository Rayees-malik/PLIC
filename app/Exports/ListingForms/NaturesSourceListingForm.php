<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class NaturesSourceListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/natures_source.xlsx';

    protected $filename = 'natures_source_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'A';

    protected $worksheetIndex = 1;

    protected $priceCode = 'SOURCE';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'countryOrigin',
                'dimensions',
                'uom',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
                },
                'regulatoryInfo' => function ($query) {
                    $query->select('product_id', 'npn');
                },
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'brand' => function ($query) {
                    return $query->with([
                        'as400SpecialPricing' => function ($query) {
                            $query->byCode($this->priceCode)->forDate();
                        },
                        'brokers' => function ($query) {
                            $query->select('id', 'name');
                        },
                    ])->select('id', 'name', 'name_fr');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
            ])
            ->select(
                'id',
                'name',
                'name_fr',
                'stock_id',
                'brand_id',
                'upc',
                'size',
                'uom_id',
                'description',
                'description_fr',
                'country_origin',
                'ingredients',
                'recommended_use',
                'purity_sell_by_unit',
                'inner_units',
                'master_units',
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

            $ogd = $product->getOGD($this->priceCode)['discount'];
            $discount = $ogd ?? 'x';

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            $brokers = optional($product->brand->brokers)->pluck('name')->toArray();

            $data[] = [
                'A' => 'Purity Life',
                'B' => $product->brand->name,
                'C' => $product->brand->name . ' ' . $product->name, //count($brokers) ? implode(', ', $brokers) : 'x',
                'D' => 'x', //$product->upc,
                'E' => round($product->size, 2) ?? 1,
                'F' => $product->uom->unit,
                'G' => $product->upc,
                'H' => $product->case_upc,
                'I' => 'Each',
                'J' => optional($product->as400Pricing)->taxable === 1 ? 'Y' : 'N',
                'K' => null,
                'L' => null,
                'M' => null,
                'N' => null,
                'O' => count($brokers) ? implode(', ', $brokers) : 'x',
                'P' => $product->stock_id,
                'Q' => $product->soldByCase ? 'Case' : 'Each',
                'R' => 'x',
                'S' => $unitPrice, //optional($product->dimensions)->gross_weight * 1000 ?? null,
                'T' => $price, //optional($product->dimensions)->imperialWidth,
                'U' => 'x', //optional($product->dimensions)->imperialDepth,
                'V' => 'x', //optional($product->dimensions)->imperialHeight,
                'W' => 'x',
                'X' => 'x', //optional($product->countryOrigin)->name,
                'Y' => 'x',
                'Z' => 'x', //'Each',
                'AA' => 'x',
                'AB' => $product?->countryOrigin?->name, //$product->caseSize,
                'AC' => $product->description,
                'AD' => null,
                'AE' => null,
                'AF' => $product->recommended_use,
                'AG' => null,
                'AH' => 'x',
                'AI' => 'x',
                'AJ' => optional($product->regulatoryInfo)->npn,
                'AK' => in_array('GMO Free', $certifications) ? 'Yes' : 'No',
                'AL' => in_array('Vegan', $certifications) ? 'Yes' : 'No',
                'AM' => in_array('Vegetarian', $certifications) ? 'Yes' : 'No',
                'AN' => in_array('Organic', $certifications) ? 'Yes' : 'No',
                'AO' => 'x',
                'AP' => in_array('Gluten Free', $certifications) ? 'Yes' : 'No',
                'AQ' => 'x',
                'AR' => in_array('Kosher', $certifications) ? 'Yes' : 'No',
                'AS' => in_array('Halal', $certifications) ? 'Yes' : 'No',
                'AT' => in_array('Tree Nuts', $doesNotContain) && in_array('Peanuts', $doesNotContain) ? 'Yes' : 'No',
                'AU' => 'x',
                'AV' => in_array('Soy', $doesNotContain) ? 'Yes' : 'No',
                'AW' => 'x',
                'AX' => 'x',
                'AY' => 'x', //$product->stock_id && $product->getMedia('product')->count() ? route('products.image', $product->stock_id) : 'x',
                'AZ' => 'x', //$product->recommended_use,
                'BA' => 'x',
                'BB' => 'x',
                'BC' => 'x',
                'BD' => optional($product->countryOrigin)->name === 'Canada' ? 'Yes' : 'No',
                'BE' => null,
                'BF' => null,
                'BG' => null,
                'BH' => null,
                'BI' => optional($product->dimensions)->gross_weight * 1000 ?? null,
                'BJ' => null,
                'BK' => null,
                'BL' => null,
                'BM' => null,
            ];
        }

        return collect($data)->map(fn ($row) => array_values($row))->toArray();
    }
}
