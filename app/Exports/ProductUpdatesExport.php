<?php

namespace App\Exports;

use App\Helpers\ExcelHelper;
use App\Helpers\SignoffStateHelper;
use App\Models\Signoff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductUpdatesExport extends BaseExport
{
    public function export(Request $request)
    {
        // increase memory limit/execution time for this export
        ini_set('memory_limit', '4196M');
        ini_set('max_execution_time', '300');

        $spreadsheet = $this->loadFile('templates/product_updates.xlsx');
        $updatedProductsSheet = $spreadsheet->getSheet(0);
        $updatedProductsOldSheet = $spreadsheet->getSheet(1);

        $updatedProductsData = [];
        $updatedProductsOldData = [];

        $sinceDate = Carbon::parse($request->start_date);

        if ($sinceDate < Carbon::parse('10 weeks ago')) {
            $sinceDate = Carbon::parse('10 weeks ago');
        }

        $today = Carbon::now();

        $updatedProductsSheet->setCellValue('A2', "{$sinceDate->toFormattedDateString()} - {$today->toFormattedDateString()}");
        $updatedProductsOldSheet->setCellValue('A2', "{$sinceDate->toFormattedDateString()} - {$today->toFormattedDateString()}");

        $stockIds = array_filter(explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->get('stock_ids')))));
        $brandIds = Arr::wrap($request->get('brand_id'));
        $includeNonCatalogue = $request->include_noncatalogue == 1;

        $signoffs = Signoff::where('signoffs.state', SignoffStateHelper::APPROVED)
            ->where('signoffs.initial_type', \App\Models\Product::class)
            ->where('signoffs.new_submission', 0)
            ->whereDate('signoffs.updated_at', '>=', $sinceDate)
            ->leftJoin('signoffs as previousSignoff', function ($join) {
                $join->on('previousSignoff.initial_id', 'signoffs.initial_id')
                    ->on('previousSignoff.initial_type', 'signoffs.initial_type')
                    ->on('previousSignoff.updated_at', '=',
                        DB::raw('(select max(updated_at) from signoffs as sub_signoffs where sub_signoffs.initial_id = signoffs.initial_id and ' .
                            'sub_signoffs.initial_type = signoffs.initial_type and sub_signoffs.updated_at < signoffs.updated_at and sub_signoffs.state = ' .
                            SignoffStateHelper::APPROVED . ')')
                    )
                    ->where('previousSignoff.state', SignoffStateHelper::APPROVED);
            })
            ->with([
                'initial' => function ($query) {
                    $query->with([
                        'as400StockData',
                        'as400Pricing',
                    ])->select('id');
                },
                'proposed' => function ($query) {
                    $query->with([
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
                        'media',
                        'brand' => function ($query) {
                            $query->with('media', function ($query) {
                                $query->where('collection_name', 'logo');
                            })->select('id', 'name', 'description', 'made_in_canada');
                        },
                    ]);
                },
            ])
            ->select('signoffs.id as id', 'signoffs.initial_id as initial_id', 'signoffs.initial_type as initial_type',
                'signoffs.proposed_id as proposed_id', 'signoffs.proposed_type as proposed_type', 'signoffs.state as state',
                'signoffs.updated_at as updated_at', 'previousSignoff.id as previousSignoffId');

        if ($stockIds || $brandIds || ! $includeNonCatalogue) {
            $signoffs->whereHasMorph('initial', \App\Models\Product::class, function ($query) use ($stockIds, $brandIds, $includeNonCatalogue) {
                if (! $includeNonCatalogue) {
                    $query->catalogueActive();
                }

                if ($stockIds) {
                    $query->whereIn('stock_id', $stockIds);
                } elseif ($brandIds) {
                    $query->whereIn('brand_id', $brandIds);
                }
            });
        }

        $signoffs = $signoffs->get();

        $previousSignoffIds = array_filter($signoffs->pluck('previousSignoffId')->toArray());
        $previousSignoffs = Signoff::with([
            'proposed' => function ($query) {
                $query->with([
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
                    'media',
                    'brand' => function ($query) {
                        $query->with('media', function ($query) {
                            $query->where('collection_name', 'logo');
                        })->select('id', 'name', 'description', 'made_in_canada');
                    },
                ]);
            },
        ])->find($previousSignoffIds);

        $signoffsByBrand = $signoffs
            ->sortBy('proposed.brand.name', SORT_NATURAL | SORT_FLAG_CASE)
            ->groupBy('brand.name');

        $highlightData = [];
        foreach ($signoffsByBrand as $brandSignoffs) {
            $brandSignoffs->sortBy('stock_id');

            foreach ($brandSignoffs as $signoff) {
                $previousSignoff = $signoff->previousSignoffId ? $previousSignoffs->find($signoff->previousSignoffId) : null;

                $updatedData = $this->getData($signoff->proposed, $signoff->initial, optional($signoff->initial->as400Pricing)->wholesale_price, $signoff->updated_at);
                $oldData = [];

                if ($previousSignoff) {
                    $oldData = $this->getData($previousSignoff->proposed, $signoff->initial, $signoff->proposed->old_wholesale_price);

                    $highlightData[] = array_map(function ($a, $b) {
                        return $a !== $b;
                    }, $updatedData, $oldData);
                } else {
                    $highlightData[] = [];
                }

                $updatedProductsData[] = $updatedData;
                $updatedProductsOldData[] = $oldData;
            }
        }
        $updatedProductsSheet->fromArray($updatedProductsData, null, 'A4');
        $updatedProductsOldSheet->fromArray($updatedProductsOldData, null, 'A4');

        foreach ($highlightData as $i => $cols) {
            $row = 4 + $i;

            if (count($cols) == 0) {
                $updatedProductsSheet->getStyle("A{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFA500');
            } else {
                array_pop($cols); // to remove Updated At column
                foreach ($cols as $y => $changed) {
                    $col = ExcelHelper::indexToColumn($y);
                    $changed && $updatedProductsSheet->getStyle("{$col}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('EA7057');
                }
            }
        }

        $dateString = $sinceDate->format('m/d/Y');
        $nowDateString = Carbon::now()->format('m/d/Y');

        return $this->downloadFile($spreadsheet, "product_updates_{$dateString}-{$nowDateString}.xlsx");
    }

    public function getData($product, $initial, $wholesalePrice, $date = null)
    {
        $retailerReceives = 1;
        $retailerOrderBy = 1;
        if ($product->soldByCase) {
            $retailerReceives = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
        } else {
            $retailerOrderBy = $product->inner_units < 2 ? $product->master_units : $product->inner_units;
        }

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
            optional($initial->as400StockData)->status,
            optional($product->regulatoryInfo)->npn,
            $product->packaging_language,
            $wholesalePrice,
            $unitPrice,
            optional($initial->as400Pricing)->taxable ? 'taxable (1.1)' : 'non taxable (4.1)',
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
            $date,
        ];
    }
}
