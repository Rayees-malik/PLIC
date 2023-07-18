<?php

use App\Actions\Database\RemoveDiscontinuedProductPromosAction;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PromoLineItem;
use App\Models\PromoPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('removes promo line items on promos starting in the future when a product is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    // create a promo with line item for a discontinued product of the brand in the future
    $product = Product::factory()->for($brand)->discontinued()->create();
    $promoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($promo, 'promo')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(1);

    // promo has 1 line item
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(1);

    //## Act
    // run the action
    app(RemoveDiscontinuedProductPromosAction::class)->execute();

    //## Assert
    // brand has 1 promo
    expect($brand->fresh()->promos()->count())->toBe(1);

    // brand promo has no line items
    expect($promo->refresh()->lineItems()->count())->toBe(0);
    $this->assertSoftDeleted($promoLineItem);
});

it('does not remove promo line items on promos starting in the future when a product is not discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo with line item for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    // create a promo with line item for a discontinued product of the brand in the future
    $discontinuedProduct = Product::factory()->for($brand)->discontinued()->create();
    $discontinuedPromoLineItem = PromoLineItem::factory()
        ->for($discontinuedProduct, 'product')
        ->for($promo, 'promo')
        ->create();

    $activeProduct = Product::factory()->for($brand)->active()->create();
    $activePromoLineItem = PromoLineItem::factory()
        ->for($activeProduct, 'product')
        ->for($promo, 'promo')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(1);

    // promo has 1 line item
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(2);

    //## Act
    // run the action
    app(RemoveDiscontinuedProductPromosAction::class)->execute();

    //## Assert
    // brand has 1 promo
    expect($brand->fresh()->promos()->count())->toBe(1);

    // brand promo has no line items
    expect($brand->fresh()->promos()->first()->lineItems()->count())->toBe(1);
    $this->assertModelExists($activePromoLineItem);
    $this->assertSoftDeleted($discontinuedPromoLineItem);
});

it('does not remove promo line items on promos starting in the past and ending in the future when a product is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInPast()->endsInFuture(), 'period')
        ->create();

    // create a promo with line item for a discontinued product of the brand in the future
    $product = Product::factory()->for($brand)->discontinued()->create();
    $promoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($promo, 'promo')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(1);

    // promo has 1 line item
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(1);

    //## Act
    // run the action
    app(RemoveDiscontinuedProductPromosAction::class)->execute();

    //## Assert
    // brand has 1 promo
    expect($brand->fresh()->promos()->count())->toBe(1);

    // brand promo has no line items
    expect($promo->refresh()->lineItems()->count())->toBe(1);
    $this->assertNotSoftDeleted($promoLineItem);
});

it('does not remove promo line items on expired promos when product is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $expiredPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->endsInPast(), 'period')
        ->create();

    $activePromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    // create a promo with line item for a discontinued product of the brand in the future
    $product = Product::factory()->for($brand)->discontinued()->create();

    $expiredPromoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($expiredPromo, 'promo')
        ->create();

    $activePromoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($activePromo, 'promo')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(2);

    //## Act
    // run the action
    app(RemoveDiscontinuedProductPromosAction::class)->execute();

    //## Assert
    expect($activePromo->refresh()->lineItems()->count())->toBe(0);
    expect($expiredPromo->refresh()->lineItems()->count())->toBe(1);
    $this->assertNotSoftDeleted($expiredPromoLineItem);
    $this->assertSoftDeleted($activePromoLineItem);
});

it('does not remove promo line items on expired promos in the past and ending in the future when a product is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $skippedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInPast()->endsInFuture(), 'period')
        ->create();

    $activePromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    // create a promo with line item for a discontinued product of the brand in the future
    $product = Product::factory()->for($brand)->discontinued()->create();

    $skippedPromoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($skippedPromo, 'promo')
        ->create();

    $activePromoLineItem = PromoLineItem::factory()
        ->for($product, 'product')
        ->for($activePromo, 'promo')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(2);

    //## Act
    // run the action
    app(RemoveDiscontinuedProductPromosAction::class)->execute();

    //## Assert
    expect($activePromo->refresh()->lineItems()->count())->toBe(0);
    expect($skippedPromo->refresh()->lineItems()->count())->toBe(1);
    $this->assertNotSoftDeleted($skippedPromoLineItem);
    $this->assertSoftDeleted($activePromoLineItem);
});
