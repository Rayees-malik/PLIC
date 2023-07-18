<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class SobeysListingForm extends SimpleListingForm
{
    protected $template = 'templates/listingforms/sobeys.xlsx';

    protected $filename = 'sobeys_listingform.xlsx';

    protected $startingRow = 8;

    protected $startingColumn = 'B';

    protected $worksheetIndex = 2;

    protected $priceCode = 'SOBEYSON';

    protected $extraData = [];

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'uom',
                'dimensions',
                'innerDimensions',
                'masterDimensions',
                'allergens' => function ($query) {
                    $query->wherePivot('contains', -1);
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
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
                'subcategory' => function ($query) {
                    $query->select('id', 'category');
                },
            ])
            ->select(
                'id', 'master_upc', 'upc', 'name', 'brand_id', 'size',
                'uom_id', 'name_fr', 'purity_sell_by_unit', 'country_origin',
                'inner_upc', 'inner_units', 'master_upc', 'master_units',
                'cases_per_tie', 'layers_per_skid', 'subcategory_id',
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

            $baseVolume = round(
                optional($product->dimensions)->depth
                 * optional($product->dimensions)->width
                 * optional($product->dimensions)->height, 3);

            $innerVolume = round(
                optional($product->innerDimensions)->depth
                 * optional($product->innerDimensions)->width
                 * optional($product->innerDimensions)->height, 3);

            $masterVolume = round(
                optional($product->masterDimensions)->depth
                 * optional($product->masterDimensions)->width
                 * optional($product->masterDimensions)->height, 3);

            $certifications = $product->certifications->pluck('name')->toArray();
            $doesNotContain = $product->allergens->pluck('name')->toArray();

            $taxable = optional($product->as400Pricing)->taxable ? 'Y' : 'N';
            $brandNameFR = $product->brand->name_fr ?? $product->brand->name;

            $data[] = [
                $product->master_upc,
                $product->upc,
                "{$product->name} {$product->brand->name} {$product->getSize()}",
                $product->name_fr ? "{$product->name_fr} {$brandNameFR} {$product->getSize()}" : null,
                $product->brand->name,
                $product->brand->name_fr,
                null,
                null,
                $product->soldByCase ? 'CS' : 'EA',
                null,
                $price,
                'CS',
                'CAD',
                $taxable,
                null,
                $taxable,
                $taxable,
                null,
                null,
                null,
                null,
                round($product->size, 2),
                optional($product->uom)->unit,
                null,
                null,
                optional($product->countryOrigin)->name,
                null,
                null,
                null,
                null,
                'EA',
                round(optional($product->dimensions)->gross_weight, 3),
                round(optional($product->dimensions)->net_weight ?? optional($product->dimensions)->gross_weight, 3),
                round(optional($product->dimensions)->width, 3),
                round(optional($product->dimensions)->depth, 3),
                round(optional($product->dimensions)->height, 3),
                $baseVolume,

                $product->inner_upc ? 'Carton' : null,
                $product->inner_upc,
                $product->inner_units,
                round(optional($product->innerDimensions)->gross_weight, 3),
                round(optional($product->innerDimensions)->gross_weight, 3),
                round(optional($product->innerDimensions)->width, 3),
                round(optional($product->innerDimensions)->depth, 3),
                round(optional($product->innerDimensions)->height, 3),
                $innerVolume,

                $product->master_upc ? 'Case' : null,
                $product->master_units,
                round(optional($product->masterDimensions)->gross_weight, 3),
                round(optional($product->masterDimensions)->gross_weight, 3),
                round(optional($product->masterDimensions)->width, 3),
                round(optional($product->masterDimensions)->depth, 3),
                round(optional($product->masterDimensions)->height, 3),
                $masterVolume,

                $product->cases_per_tie > 0 ? 'LAY' : null,
                $product->cases_per_tie,
                round($masterVolume * $product->cases_per_tie, 1),
                round($masterVolume * $product->cases_per_tie, 1),

                $product->layers_per_skid > 0 ? 'PAL' : null,
                $product->layers_per_skid,
                round($masterVolume * $product->cases_per_tie * $product->layers_per_skid, 1),
                round($masterVolume * $product->cases_per_tie * $product->layers_per_skid, 1),

                in_array('Gluten Free', $certifications) ? 'Y' : 'N',
                in_array('Kosher', $certifications) ? 'Y' : 'N',
                in_array('Organic', $certifications) ? 'Y' : 'N',
                in_array('Halal', $certifications) ? 'Y' : 'N',
                null,
                in_array('Tree Nuts', $doesNotContain) || in_array('Peanuts', $doesNotContain) ? 'Y' : 'N',
                in_array('Peanuts', $doesNotContain) ? 'Y' : 'N',
                in_array('Vegan', $certifications) ? 'Y' : 'N',
                in_array('Vegetarian', $certifications) ? 'Y' : 'N',
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
                'Purity Life Health Products LP',
                '203770',
                optional($product->subcategory)->category,
            ];

            $this->extraData[] = [
                $product->upc,
                "{$product->name} {$product->brand->name} {$product->getSize()}",
            ];
        }

        $this->rowCount = count($data);

        return $data;
    }

    public function onExportComplete($spreadsheet)
    {
        $sheetMarketing = $spreadsheet->getSheet(3);
        $sheetMarketing->fromArray($this->extraData, null, 'A7');

        $sheetNutrifact = $spreadsheet->getSheet(4);
        $sheetNutrifact->fromArray($this->extraData, null, 'A6');
    }
}
