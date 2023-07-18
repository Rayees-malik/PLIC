<?php

use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\Brand;
use App\Models\FutureLandedCost;
use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(DatabaseTransactions::class);

it('gets the correct landed cost when there are no applicable future landed costs', function () {
    $product = Product::factory()
        ->create([
            'landed_cost' => 5,
        ]);

    expect($product->futureLandedCosts->count())->toEqual(0);
    expect($product->getLandedCost('2022-01-01'))->toEqual(5);

    $product->futureLandedCosts()
        ->save(
            FutureLandedCost::factory()
                ->state(fn (array $attributes) => [
                    'change_date' => '2022-02-01',
                    'landed_cost' => 10,
                ])
                ->make()
        );

    expect($product->refresh()->futureLandedCosts->count())->toEqual(1);
    expect($product->getLandedCost('2022-01-01'))->toEqual(5);
});

it('gets the correct landed cost when there is only one applicable future landed cost', function () {
    $product = Product::factory()
        ->has(FutureLandedCost::factory()->state(
            fn (array $attributes) => [
                'change_date' => '2022-01-01',
                'landed_cost' => 10,
            ]
        ))
        ->create([
            'landed_cost' => 5,
        ]);

    expect($product->futureLandedCosts->count())->toEqual(1);
    expect($product->getLandedCost('2022-02-01'))->toEqual(10);

    $product->futureLandedCosts()
        ->save(
            FutureLandedCost::factory()
                ->state(fn (array $attributes) => [
                    'change_date' => '2022-03-01',
                    'landed_cost' => 15,
                ])
                ->make()
        );

    expect($product->refresh()->futureLandedCosts->count())->toEqual(2);
    expect($product->getLandedCost('2022-02-01'))->toEqual(10);
});

it('gets the correct landed cost when there are more than one applicable future landed costs', function () {
    $product = Product::factory()
        ->has(FutureLandedCost::factory()
            ->count(2)
            ->state(
                new Sequence(
                    ['change_date' => '2022-01-01', 'landed_cost' => 10],
                    ['change_date' => '2022-01-15', 'landed_cost' => 15],
                )
            ))
        ->create([
            'landed_cost' => 5,
        ]);

    expect($product->futureLandedCosts->count())->toEqual(2);
    expect($product->getLandedCost('2022-02-01'))->toEqual(15);

    $product->futureLandedCosts()
        ->save(
            FutureLandedCost::factory()
                ->state(fn (array $attributes) => [
                    'change_date' => '2022-02-01',
                    'landed_cost' => 20,
                ])
                ->make()
        );

    expect($product->refresh()->futureLandedCosts->count())->toEqual(3);
    expect($product->getLandedCost('2022-02-01'))->toEqual(20);
});

it('returns the correct previous step from webseries upload step', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 5,
        ]);

    // $previousStep = $product->prevStep(5, $signoff);
    // expect($previousStep)->toEqual(1);

    // $previousStep = $product->prevStep(4, $signoff);
    // expect($previousStep)->toEqual(3);

    // $this->mock(Product::class, function ($mock) use ($product) {
    //     $mock->shouldReceive('getRequiresQCSignoffAttribute')
    //         ->andReturn(true);
    // });

    $previousStep = $product->prevStep(5, $signoff);
    expect($previousStep)->toEqual(1);
});

it('returns the correct previous step from management step', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 4,
        ]);

    $previousStep = $product->prevStep(4, $signoff);
    expect($previousStep)->toEqual(3);
});

it('returns the correct previous step from finance step and qc not required', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create([
        'category_id' => 1,
    ]);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 3,
        ]);

    $previousStep = $product->prevStep(3, $signoff);
    expect($previousStep)->toEqual(1);
});

it('returns the correct previous step from finance step and qc required', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create([
        'category_id' => 3,
    ]);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 3,
        ]);

    $previousStep = $product->prevStep(3, $signoff);
    expect($previousStep)->toEqual(2);
});

it('returns the correct previous step from vendor relations step', function () {
    $user = User::factory()->create();

    $product = Product::factory()->catalogueActive()->create([
        'category_id' => 3,
    ]);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($user)
        ->for($product, 'initial')
        ->for($product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->create([
            'new_submission' => true,
            'step' => 1,
        ]);

    $previousStep = $product->prevStep(1, $signoff);
    expect($previousStep)->toEqual(0);
});

/** Scope tests */
test('catalogue active scope includes AS400 active products', function () {
    $brand = Brand::factory()->create();

    $product = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->active(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->contains($product))->toBe(true);
    expect($products->count())->toBe(1);
});

test('catalogue active scope includes AS400 discontinued products with out of stock flag set', function () {
    $brand = Brand::factory()->create();

    $product = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->discontinued()->inStock(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(1);
    expect($products->contains($product))->toBe(true);
});

test('catalogue active scope includes AS400 superseded products with out of stock flag set', function () {
    $brand = Brand::factory()->create();

    $product = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->superseded()->inStock(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(1);
    expect($products->contains($product))->toBe(true);
});

test('catalogue active scope includes AS400 discontinued products with warehouse stock', function () {
    $brand = Brand::factory()->create();

    $product = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->discontinued(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(1);
    expect($products->contains($product))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
});

test('catalogue active scope includes AS400 superseded products with warehouse stock', function () {
    $brand = Brand::factory()->create();

    $product = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->superseded(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(1);
    expect($products->contains($product))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
});

test('catalogue active scope includes AS400 active products and discontinued products with warehouse stock', function () {
    $brand = Brand::factory()->create();

    $activeProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->active(), 'as400StockData')
        ->create();

    $discontinuedProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 52]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->discontinued(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(2);
    expect($products->contains($activeProduct))->toBe(true);
    expect($products->contains($discontinuedProduct))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
    expect($products->last()->as400WarehouseStock->first()->quantity)->toBe(52);
});

test('catalogue active scope includes AS400 active products and superseded products with warehouse stock', function () {
    $brand = Brand::factory()->create();

    $activeProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->active(), 'as400StockData')
        ->create();

    $supersededProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 52]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->superseded(), 'as400StockData')
        ->create();

    $products = Product::catalogueActive()->get();

    expect($products->count())->toBe(2);
    expect($products->contains($activeProduct))->toBe(true);
    expect($products->contains($supersededProduct))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
    expect($products->last()->as400WarehouseStock->first()->quantity)->toBe(52);
});

test('catalogue active scope includes active non catalogue products when parameter is provided', function () {
    $brand = Brand::factory()->create();

    $catalogueProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->active()->state(fn (array $attributes) => ['hide_from_catalogue' => false]), 'as400StockData')
        ->create();

    $nonCatalogueProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 52]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->active()->state(fn (array $attributes) => ['hide_from_catalogue' => true]), 'as400StockData')
        ->create();

    $products = Product::catalogueActive(true)->get();

    expect($products->count())->toBe(2);
    expect($products->contains($catalogueProduct))->toBe(true);
    expect($products->contains($nonCatalogueProduct))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
    expect($products->last()->as400WarehouseStock->first()->quantity)->toBe(52);
});

test('catalogue active scope includes discontinued and superseded non catalogue products with warehouse stock when parameter is provided', function () {
    $brand = Brand::factory()->create();

    $discontinuedProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 25]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->discontinued()->state(fn (array $attributes) => ['hide_from_catalogue' => true]), 'as400StockData')
        ->create();

    $supersededProduct = Product::factory()
        ->for($brand)
        ->has(AS400WarehouseStock::factory()->state(fn (array $attributes) => ['quantity' => 52]), 'as400WarehouseStock')
        ->has(AS400StockData::factory()->superseded()->state(fn (array $attributes) => ['hide_from_catalogue' => true]), 'as400StockData')
        ->create();

    $products = Product::catalogueActive(true)->get();

    expect($products->count())->toBe(2);
    expect($products->contains($discontinuedProduct))->toBe(true);
    expect($products->contains($supersededProduct))->toBe(true);
    expect($products->first()->as400WarehouseStock->first()->quantity)->toBe(25);
    expect($products->last()->as400WarehouseStock->first()->quantity)->toBe(52);
});

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    Product::withAccess()->get();
})->throws(HttpException::class);
