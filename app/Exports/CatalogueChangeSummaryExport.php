<?php

namespace App\Exports;

use App\Helpers\SignoffStateHelper;
use App\Models\AS400\AS400UpcomingPriceChange;
use App\Models\BrandDiscoRequest;
use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\Models\Signoff;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogueChangeSummaryExport extends BaseExport
{
    public function export(Request $request)
    {
        $spreadsheet = $this->loadFile('templates/catalogue_change_summary.xlsx');
        $newProductsSheet = $spreadsheet->getSheet(0);
        $delistsSheet = $spreadsheet->getSheet(1);
        $brandDiscosSheet = $spreadsheet->getSheet(2);
        $newPriceChangesSheet = $spreadsheet->getSheet(3);
        $allPriceChangesSheet = $spreadsheet->getSheet(4);

        $newProductsData = [];
        $delistsData = [];
        $newPriceChangesData = [];
        $allPriceChangesData = [];
        $discosData = [];

        $startDate = $request->get('start_date') ? new Carbon($request->get('start_date')) : Carbon::parse('30 days ago');

        $today = Carbon::now();

        // New Products
        $products = Product::with(
            [
                'uom',
                'as400Pricing',
                'as400Supersedes' => function ($query) {
                    $query->select('products.id', 'stock_id');
                },
                'brand' => function ($query) {
                    $query->select('id', 'name');
                },
            ]
        )
            ->catalogueActive()
            ->whereDate('listed_on', '>=', $startDate)
            ->select('id', 'brand_id', 'name', 'name_fr', 'packaging_language', 'stock_id', 'size', 'uom_id', 'upc', 'purity_sell_by_unit', 'inner_units', 'master_units')
            ->get();

        foreach ($products as $product) {
            $newProductsData[] = [
                $product->brand->name,
                $product->stock_id,
                $product->getName(),
                $product->getSizeWithUnits(),
                $product->upc,
                optional($product->as400Pricing)->wholesale_price,
                optional($product->as400Supersedes->first())->stock_id,
            ];
        }

        // Delists
        $delists = ProductDelistRequest::with(
            [
                'product' => function ($query) {
                    $query->with(
                        [
                            'uom',
                            'as400Pricing',
                            'as400Supersedes' => function ($query) {
                                $query->select('products.id', 'stock_id');
                            },
                            'brand' => function ($query) {
                                $query->select('id', 'name');
                            },
                            'as400WarehouseStock' => function ($query) {
                                $query->select('id', 'product_id', 'warehouse', 'quantity');
                            },
                        ]
                    )
                        ->select('id', 'brand_id', 'name', 'name_fr', 'packaging_language', 'stock_id', 'size', 'uom_id', 'upc', 'purity_sell_by_unit', 'inner_units', 'master_units');
                },
            ]
        )
            ->whereDate('updated_at', '>=', $startDate)
            ->get();

        foreach ($delists as $delist) {
            $product = $delist->product;

            $warehouseTotals = [
                1 => 0,
                4 => 0,
                8 => 0,
                9 => 0,
            ];

            foreach ($product->as400WarehouseStock as $warehouseStock) {
                $warehouseTotals[$warehouseStock->warehouse] = $warehouseStock->quantity;
            }

            $delistsData[] = [
                $product->brand->name,
                $product->stock_id,
                $product->getName(),
                $product->getSizeWithUnits(),
                $product->upc,
                optional($product->as400Pricing)->wholesale_price,
                $delist->reason,
                optional($product->as400Supersedes->first())->stock_id,
                ...$warehouseTotals,
                $delist->updated_at->format('Y-m-d'),
            ];
        }

        // Brand Disco
        $discos = BrandDiscoRequest::with(
            [
                'brand' => function ($query) {
                    $query->select('id', 'name', 'brand_number');
                },
            ]
        )
            ->whereDate('updated_at', '>=', $startDate)
            ->get();

        foreach ($discos as $disco) {
            $brand = $disco->brand;
            $discosData[] = [
                $brand->name,
                $brand->brand_number,
                $disco->reason,
                $disco->updated_at->format('Y-m-d'),
            ];
        }

        // New Price Changes
        $priceChanges = Signoff::with([
            'proposed' => function ($query) {
                $query->with([
                    'uom',
                    'brand' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])->select(
                    'id',
                    'brand_id',
                    'name',
                    'name_fr',
                    'packaging_language',
                    'stock_id',
                    'size',
                    'uom_id',
                    'upc',
                    'purity_sell_by_unit',
                    'inner_units',
                    'master_units',
                    'wholesale_price',
                    'old_wholesale_price',
                    'price_change_date',
                    'price_change_reason'
                );
            },
        ])
            ->whereHasMorph('proposed', Product::class, function ($query) {
                $query->allStates()->whereHas('ledgers', function ($query) {
                    $query->where('properties->unit_cost', '>', 0);
                });
            })
            ->where(['state' => SignoffStateHelper::APPROVED, 'new_submission' => 0])
            ->whereDate('updated_at', '>=', $startDate)
            ->orderBy('id', 'desc')
            ->get();

        foreach ($priceChanges as $priceChange) {
            $proposed = $priceChange->proposed;

            if (! $proposed->price_change_date || ! $proposed->price_change_reason || $proposed->wholesale_price == $proposed->old_wholesale_price) {
                continue;
            }

            $data = [
                $proposed->brand->name,
                $proposed->stock_id,
                $proposed->getName(),
                $proposed->getSizeWithUnits(),
                $proposed->upc,
                $proposed->wholesale_price,
                $proposed->old_wholesale_price,
                $proposed->price_change_date->format('Y-m-d'),
                $proposed->price_change_reason,
            ];

            $newPriceChangesData[] = $data;
        }

        // All Upcoming Price Changes
        $priceChanges = AS400UpcomingPriceChange::with([
            'product' => function ($query) {
                $query->with([
                    'uom',
                    'as400Pricing',
                    'signoffs' => function ($query) {
                        $query->with([
                            'proposed' => function ($query) {
                                $query->with([
                                    'ledgers' => function ($query) {
                                        $query->orderBy('id', 'desc')->limit(1);
                                    },
                                ])->select('id', 'price_change_reason', 'wholesale_price');
                            },
                        ])->whereHasMorph('proposed', Product::class, function ($query) {
                            $query->allStates()->whereHas('ledgers', function ($query) {
                                $query->where('properties->unit_cost', '>', 0);
                            });
                        })->where(['state' => SignoffStateHelper::APPROVED, 'new_submission' => 0])->orderBy('id', 'desc')->limit(1);
                    },
                    'brand' => function ($query) {
                        $query->select('id', 'name');
                    },
                ])->select('id', 'brand_id', 'name', 'name_fr', 'packaging_language', 'stock_id', 'size', 'uom_id', 'upc', 'purity_sell_by_unit', 'inner_units', 'master_units');
            },
        ])->whereHas('product')->get();

        foreach ($priceChanges as $priceChange) {
            $product = $priceChange->product;
            $signoff = $product->signoffs->first();
            $proposed = $signoff ? $signoff->proposed : null;

            if ($proposed && $proposed->price_change_date != $priceChange->change_date && $proposed->wholesale_price != $priceChange->wholesale_price) {
                $proposed = null;
            }

            $data = [
                $product->brand->name,
                $product->stock_id,
                $product->getName(),
                $product->getSizeWithUnits(),
                $product->upc,
                $priceChange->wholesale_price,
                optional($proposed)->old_wholesale_price ? $proposed->old_wholesale_price : optional($product->as400Pricing)->wholesale_price,
                $priceChange->change_date->format('Y-m-d'),
                $proposed ? $proposed->price_change_reason : null,
            ];

            $allPriceChangesData[] = $data;
        }

        $newProductsSheet->setCellValue('A2', "New Products - {$startDate->toFormattedDateString()} - {$today->toFormattedDateString()}");
        $newProductsSheet->fromArray($newProductsData, null, 'A5');

        $delistsSheet->setCellValue('A2', "Product Delists - {$startDate->toFormattedDateString()} - {$today->toFormattedDateString()}");
        $delistsSheet->fromArray($delistsData, null, 'A5', true);

        $brandDiscosSheet->setCellValue('A2', "Brand Discos - {$startDate->toFormattedDateString()} - {$today->toFormattedDateString()}");
        $brandDiscosSheet->fromArray($discosData, null, 'A5');

        $newPriceChangesSheet->setCellValue('A2', "New Price Changes Added Since {$startDate->toFormattedDateString()}");
        $newPriceChangesSheet->fromArray($newPriceChangesData, null, 'A5');

        $allPriceChangesSheet->setCellValue('A2', 'All Upcoming Price Changes');
        $allPriceChangesSheet->fromArray($allPriceChangesData, null, 'A5');

        return $this->downloadFile($spreadsheet, 'catalogue_change_history.xlsx');
    }
}
