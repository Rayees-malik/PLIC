<?php

use App\Contracts\Actions\Api\V1\RetrievesProducts;
use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

uses(DatabaseTransactions::class);

it('only includes catalogue active products', function () {
    $action = app(RetrievesProducts::class);

    $activeInStock = Product::factory()
        ->count(2)
        ->has(AS400StockData::factory()->active())
        ->warehouse(1)
        ->create();

    $activeOutOfStock = Product::factory()
        ->count(2)
        ->has(AS400StockData::factory()->active())
        ->warehouse(warehouseNumber: 1, inventoryQuantity: 0)
        ->create();

    $discontinuedInStock = Product::factory()
        ->count(2)
        ->catalogueDiscontinued()
        ->warehouse(1)
        ->create();

    $discontinuedOutOfStock = Product::factory()
        ->count(2)
        ->catalogueDiscontinued(false)
        ->warehouse(warehouseNumber: 1, inventoryQuantity: 0)
        ->create();

    $products = $action();

    expect($products)->toHaveCount(6);
    expect($products->filter(function ($product) use ($activeInStock) {
        return $activeInStock->contains('stock_id', $product['sku']);
    }))->toHaveCount(2);
    expect($products->filter(function ($product) use ($activeOutOfStock) {
        return $activeOutOfStock->contains('stock_id', $product['sku']);
    }))->toHaveCount(2);
    expect($products->filter(function ($product) use ($discontinuedInStock) {
        return $discontinuedInStock->contains('stock_id', $product['sku']);
    }))->toHaveCount(2);
    expect($products->filter(function ($product) use ($discontinuedOutOfStock) {
        return $discontinuedOutOfStock->contains('stock_id', $product['sku']);
    }))->toHaveCount(0);
});

it('only counts inventory from the Acton warehouse', function () {
    $action = app(RetrievesProducts::class);

    $actonProducts = Product::factory()
        ->catalogueActive()
        ->warehouse(1, 10)
        ->warehouse(8, 15)
        ->create();

    $products = $action();

    expect($products)->toHaveCount(1);
    expect($products->first()['available_inventory'])->toBe(10);
});

it('only includes inventory from the Acton warehouse', function () {
    $action = app(RetrievesProducts::class);

    $actonProducts = Product::factory()
        ->count(2)
        ->catalogueActive()
        ->state(new Sequence(
            ['stock_id' => 'INLCUDED_1'],
            ['stock_id' => 'INLCUDED_2'],
        ))
        ->has(AS400WarehouseStock::factory()->state(function () {
            return ['warehouse' => 1];
        }))
        ->create();

    $otherProducts = Product::factory()
        ->catalogueActive()
        ->has(AS400WarehouseStock::factory()->state(function () {
            return ['warehouse' => 8];
        }))
        ->create([
            'stock_id' => 'NOT_INCLUDED',
        ]);

    $products = $action();

    expect($products)->toHaveCount(2);
    expect($products->contains(function ($value, $key) {
        return Str::startsWith($value['sku'], 'NOT_INLCUDED');
    }))->toBeFalse();
    expect(
        $products->contains(function ($value, $key) {
            return Str::startsWith($value['sku'], 'INLCUDED_1');
        })
    )->toBeTrue();
    expect(
        $products->contains(function ($value, $key) {
            return Str::startsWith($value['sku'], 'INLCUDED_2');
        })
    )->toBeTrue();
});

it('sorts by brand name', function () {
    $action = app(RetrievesProducts::class);

    $brands = Brand::factory()->count(3)->state(new Sequence(
        ['name' => 'Z'],
        ['name' => 'A'],
        ['name' => 'P'],
    ))->create()->toArray();

    Product::factory()
        ->count(3)
        ->catalogueActive()
        ->state(new Sequence(
            fn ($sequence) => ['brand_id' => $brands[$sequence->index]['id']]
        ))
        ->warehouse(1)
        ->create();

    $products = $action();

    expect($products->values()[0]['brand'])->toBe('A');
    expect($products->values()[1]['brand'])->toBe('P');
    expect($products->values()[2]['brand'])->toBe('Z');
});

it('caches the data for one day', function () {
    $action = app(RetrievesProducts::class);

    Cache::shouldReceive('remember')
        ->once()
        ->with(get_class($action), 86400, \Closure::class)
        ->andReturn(collect());

    $action();
});

it('does not include QC warehouses', function () {
    $action = app(RetrievesProducts::class);

    $action();

    expect(collect(Cache::get('warehouses_map'))->contains(function ($item) {
        return Str::startsWith($item, 'QC');
    }))->toBeFalse();
});
