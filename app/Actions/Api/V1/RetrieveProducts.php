<?php

namespace App\Actions\Api\V1;

use App\Contracts\Actions\Api\V1\RetrievesProducts;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RetrieveProducts implements RetrievesProducts
{
    public function __invoke(): Collection
    {
        return Cache::remember(self::class, 86400, function () {
            return Product::query()
                ->select('id', 'stock_id', 'name', 'upc', 'wholesale_price', 'size', 'purity_sell_by_unit', 'inner_units', 'master_units', 'brand_id', 'uom_id')
                ->where(function ($query) {
                    $query->catalogueActive()->whereHas('as400WarehouseStock', function ($query) {
                        $query->where('warehouse', '=', 1);
                    });
                })
                ->orWhere(function ($query) {
                    $query->catalogueActive()->whereDoesntHave('as400WarehouseStock');
                })
                ->withSum([
                    'as400WarehouseStock' => function ($query) {
                        $query->where('warehouse', '=', 1);
                    },
                ], 'quantity')
                ->with([
                    'as400Pricing:id,product_id,wholesale_price',
                    'as400StockData:id,product_id,status,hide_from_catalogue',
                    'brand:id,name',
                    'as400WarehouseStock' => function ($query) {
                        $query->where('warehouse', '=', 1);
                    },
                ])
                ->get()
                ->map(function ($product) {
                    return [
                        'sku' => $product->stock_id,
                        'brand' => $product->brand->name,
                        'product_name' => $product->name,
                        'product_size' => $product->getSize(true),
                        'retail_upc' => $product->upc,
                        'available_inventory' => (int) $product->as400_warehouse_stock_sum_quantity ?? 0,
                        'current_price' => $product->getPrice(),
                        'sold_by_case' => $product->soldByCase ? 'Y' : 'N',
                        'retailer_receives_quantity' => $product->soldByCase ?
                            ($product->inner_units < 2 ? $product->master_units : $product->inner_units)
                            : 1,
                    ];
                })
                ->sortBy('brand');
        });
    }
}
