<?php

use App\Contracts\Actions\Api\V1\RetrievesProducts;
use App\Http\Controllers\Api\V1\ProductController;
use App\Models\ApiUser;
use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;

use function Pest\Laravel\getJson;

uses(DatabaseTransactions::class);

it('returns a list of catalogue active products', function () {
    Sanctum::actingAs(
        ApiUser::factory()->create()
    );

    Product::factory()
        ->for(Brand::factory(), 'brand')
        ->count(5)
        ->catalogueActive()
        ->warehouse(1)
        ->create();

    getJson(action([ProductController::class, 'index']))
        ->assertSuccessful()
        ->assertJsonCount(5, 'data');
})->shouldHaveCalledAction(RetrievesProducts::class, '__invoke');

it('requires users to authenticate with a token', function () {
    getJson(action([ProductController::class, 'index']))
        ->assertUnauthorized();
});

it('includes warehouse inventory even when there is none', function () {
    $brand = Brand::factory()->create();

    $withWarehouseRecord = Product::factory()
        ->for($brand, 'brand')
        ->catalogueActive()
        ->has(AS400Pricing::factory())
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => [
            'quantity' => 0,
            'warehouse' => 1,
        ]), 'as400WarehouseStock')
        ->create();

    $withoutWarehouseRecord = Product::factory()
        ->has(AS400Pricing::factory())
        ->for($brand, 'brand')
        ->catalogueActive()
        ->create();

    $wrongWarehouseRecord = Product::factory()
        ->for($brand, 'brand')
        ->catalogueActive()
        ->has(AS400Pricing::factory())
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => [
            'quantity' => 0,
            'warehouse' => 8,
        ]), 'as400WarehouseStock')
        ->create();

    Sanctum::actingAs(
        ApiUser::factory()->create()
    );

    getJson(action([ProductController::class, 'index']))
        ->assertSuccessful()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.available_inventory', 0)
        ->assertJsonPath('data.0.sku', (string) $withWarehouseRecord->stock_id)
        ->assertJsonPath('data.1.available_inventory', 0)
        ->assertJsonPath('data.1.sku', (string) $withoutWarehouseRecord->stock_id);
})->shouldHaveCalledAction(RetrievesProducts::class, '__invoke');
