<?php

namespace App\Exports;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductDataExport extends BaseExport
{
    public function export(Request $request)
    {
        // increase memory limit/execution time for this export
        ini_set('memory_limit', '4196M');
        ini_set('max_execution_time', '300');

        $spreadsheet = $this->loadFile('templates/product_data.xlsx');
        $allProductsSheet = $spreadsheet->getSheet(0);

        $allProductsData = [];

        $oneMonth = Carbon::parse('30 days ago');
        $today = Carbon::now();

        $allProductsSheet->setCellValue('A2', $today->toFormattedDateString());

        $stockIds = array_filter(explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->get('stock_ids')))));
        $brandIds = Arr::wrap($request->get('brand_id'));
        $includeNonCatalogue = $request->include_noncatalogue == 1;

        // All Products
        $productBrands = Product::withAccess()->catalogueActive($includeNonCatalogue)->forExport()->with(
            [
                'uom',
                'countryOrigin',
                'category',
                'subcategory',
                'catalogueCategory',
                'regulatoryInfo',
                'dimensions',
                'innerDimensions',
                'masterDimensions',
                'allergens',
                'certifications',
                'flags',
                'packagingMaterials',
                'as400StockData',
                'as400Pricing',
                'media' => function ($query) {
                    $query->whereIn('collection_name', ['product', 'label_flat']);
                },
                'brand' => function ($query) {
                    $query->with('media', function ($query) {
                        $query->where('collection_name', 'logo');
                    })->select('id', 'name', 'description', 'made_in_canada');
                },
            ]);

        if ($stockIds) {
            $productBrands->whereIn('stock_id', $stockIds);
        } elseif ($brandIds) {
            $productBrands->whereIn('brand_id', $brandIds);
        }

        $productBrands = $productBrands->get()
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        foreach ($productBrands as $products) {
            $products = $products->sortBy('stock_id');
            foreach ($products as $product) {
                $allProductsData[] = $this->getData($product);
            }
        }

        $allProductsSheet->fromArray($allProductsData, null, 'A4');

        $dateString = Carbon::now()->format('m/d/Y');

        return $this->downloadFile($spreadsheet, "product_data_{$dateString}.xlsx");
    }

    public function getData($product)
    {
        $retailerReceives = 1;
        $retailerOrderBy = 1;
        if ($product->soldByCase) {
            $retailerReceives = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
        } else {
            $retailerOrderBy = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
        }

        $wholesalePrice = optional($product->as400Pricing)->wholesale_price;
        $unitPrice = $product->sold_by_case ? ($wholesalePrice ? round($wholesalePrice / $product->caseSize, 2) : null) : null;

        $certifications = $product->certifications->pluck('name')->toArray();
        $allergens = array_column($product->allergens->toArray(), 'contains', 'name');
        $packagingMaterials = $product->packagingMaterials->pluck('name')->toArray();
        $flags = implode(',', $product->flags->pluck('name')->toArray());

        return [
            $product->stock_id,
            $product->brand->name,
            $product->brand->description,
            $product->name,
            $product->name_fr,
            $product->description,
            $product->description_fr,
            $product->features[0],
            $product->features[1],
            $product->features[2],
            $product->features[3],
            $product->features[4],
            $product->features_fr[0],
            $product->features_fr[1],
            $product->features_fr[2],
            $product->features_fr[3],
            $product->features_fr[4],
            $product->size,
            optional($product->uom)->unit,
            optional($product->uom)->unit_fr,
            $retailerReceives,
            $product->sold_by_case ? 'Y' : 'N',
            $retailerOrderBy,
            $product->upc,
            $product->inner_upc,
            $product->master_upc,
            $product->countryOrigin->name,
            $product->benefits,
            $product->benefits_fr,
            $product->contraindications,
            $product->contraindications_fr,
            $product->ingredients,
            $product->ingredients_fr,
            $product->recommended_use,
            $product->recommended_use_fr,
            $product->recommended_dosage,
            $product->recommended_dosage_fr,
            $product->warnings,
            $product->warnings_fr,
            $product->category->name,
            optional($product->catalogueCategory)->name,
            optional($product->subcategory)->code,
            optional($product->subcategory)->category,
            optional($product->subcategory)->name,
            optional($product->as400StockData)->status,
            optional($product->regulatoryInfo)->npn,
            $product->packaging_language,
            $wholesalePrice,
            $unitPrice,
            optional($product->as400Pricing)->taxable ? 'taxable (1.1)' : 'non taxable (4.1)',
            "{$product->shelf_life} {$product->shelf_life_units}",
            optional($product->dimensions)->width,
            optional($product->dimensions)->depth,
            optional($product->dimensions)->height,
            optional($product->dimensions)->gross_weight,
            optional($product->innerDimensions)->width,
            optional($product->innerDimensions)->depth,
            optional($product->innerDimensions)->height,
            optional($product->innerDimensions)->gross_weight,
            $product->inner_units,
            optional($product->masterDimensions)->width,
            optional($product->masterDimensions)->depth,
            optional($product->masterDimensions)->height,
            optional($product->masterDimensions)->gross_weight,
            $product->master_units,
            $product->cases_per_tie,
            $product->layers_per_skid,
            $product->is_display ? 'Y' : 'N',
            Arr::get($allergens, 'Egg') == 1 ? 'Y' : (Arr::get($allergens, 'Egg') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Dairy') == 1 ? 'Y' : (Arr::get($allergens, 'Dairy') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Mustard') == 1 ? 'Y' : (Arr::get($allergens, 'Mustard') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Peanuts') == 1 ? 'Y' : (Arr::get($allergens, 'Peanuts') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Seafood') == 1 ? 'Y' : (Arr::get($allergens, 'Seafood') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Sesame') == 1 ? 'Y' : (Arr::get($allergens, 'Sesame') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Soy') == 1 ? 'Y' : (Arr::get($allergens, 'Soy') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Sulfites') == 1 ? 'Y' : (Arr::get($allergens, 'Sulfites') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Tree Nuts') == 1 ? 'Y' : (Arr::get($allergens, 'Tree Nuts') == -1 ? 'N' : 'M'),
            Arr::get($allergens, 'Wheat Gluten') == 1 ? 'Y' : (Arr::get($allergens, 'Wheat Gluten') == -1 ? 'N' : 'M'),
            $product->brand->made_in_canada ? 'Y' : 'N',
            in_array('Organic', $certifications) ? 'Y' : 'N',
            in_array('GMO Free', $certifications) ? 'Y' : 'N',
            in_array('Vegetarian', $certifications) ? 'Y' : 'N',
            in_array('Vegan', $certifications) ? 'Y' : 'N',
            in_array('Fair Trade', $certifications) ? 'Y' : 'N',
            in_array('Kosher', $certifications) ? 'Y' : 'N',
            in_array('Halal', $certifications) ? 'Y' : 'N',
            in_array('Gluten Free', $certifications) ? 'Y' : 'N',
            in_array('B Corporation Certification', $certifications) ? 'Y' : 'N',
            in_array('Newsprint', $packagingMaterials) ? 'Y' : 'N',
            in_array('Magazines', $packagingMaterials) ? 'Y' : 'N',
            in_array('Directories', $packagingMaterials) ? 'Y' : 'N',
            in_array('Printed Paper', $packagingMaterials) ? 'Y' : 'N',
            in_array('Corrugate', $packagingMaterials) ? 'Y' : 'N',
            in_array('Gabletop', $packagingMaterials) ? 'Y' : 'N',
            in_array('Paper Laminants', $packagingMaterials) ? 'Y' : 'N',
            in_array('Aseptic Containers', $packagingMaterials) ? 'Y' : 'N',
            in_array('Boxboard', $packagingMaterials) ? 'Y' : 'N',
            in_array('General Use Paper', $packagingMaterials) ? 'Y' : 'N',
            in_array('PET', $packagingMaterials) ? 'Y' : 'N',
            in_array('HDPE', $packagingMaterials) ? 'Y' : 'N',
            in_array('Plastic Film', $packagingMaterials) ? 'Y' : 'N',
            in_array('Plastic Laminants', $packagingMaterials) ? 'Y' : 'N',
            in_array('Polystyrene Foam', $packagingMaterials) ? 'Y' : 'N',
            in_array('Other Plastic', $packagingMaterials) ? 'Y' : 'N',
            in_array('Food and Beverage', $packagingMaterials) ? 'Y' : 'N',
            in_array('Aerosols', $packagingMaterials) ? 'Y' : 'N',
            in_array('Other Steel', $packagingMaterials) ? 'Y' : 'N',
            in_array('Aluminum Cans', $packagingMaterials) ? 'Y' : 'N',
            in_array('Aluminum Foil', $packagingMaterials) ? 'Y' : 'N',
            in_array('Flint Glass', $packagingMaterials) ? 'Y' : 'N',
            in_array('Coloured Glass', $packagingMaterials) ? 'Y' : 'N',
            optional($product->regulatoryInfo)->serving_size,
            optional($product->regulatoryInfo)->calories ? optional($product->regulatoryInfo)->calories . 'g' : null,
            optional($product->regulatoryInfo)->total_fat ? optional($product->regulatoryInfo)->total_fat . 'g' : null,
            optional($product->regulatoryInfo)->saturated_fat ? optional($product->regulatoryInfo)->saturated_fat . 'g' : null,
            optional($product->regulatoryInfo)->trans_fat ? optional($product->regulatoryInfo)->trans_fat . 'g' : null,
            optional($product->regulatoryInfo)->cholesterol ? optional($product->regulatoryInfo)->cholesterol . 'mg' : null,
            optional($product->regulatoryInfo)->sodium ? optional($product->regulatoryInfo)->sodium . 'mg' : null,
            optional($product->regulatoryInfo)->carbohydrates ? optional($product->regulatoryInfo)->carbohydrates . 'g' : null,
            optional($product->regulatoryInfo)->fiber ? optional($product->regulatoryInfo)->fiber . 'g' : null,
            optional($product->regulatoryInfo)->sugar ? optional($product->regulatoryInfo)->sugar . 'g' : null,
            optional($product->regulatoryInfo)->protein ? optional($product->regulatoryInfo)->protein . 'g' : null,
            $flags,
            $product->getMedia('product')->count() ? route('products.image', $product->stock_id) : null,
            $product->getMedia('label_flat')->count() ? route('products.labelflat', $product->stock_id) : null,
            $product->brand->media->count() ? route('brands.logo', $product->brand->id) : null,
        ];
    }
}
