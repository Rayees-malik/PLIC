<?php

use App\Actions\Database\RemoveDiscontinuedBrandPromosAction;
use App\Models\Brand;
use App\Models\Promo;
use App\Models\PromoLineItem;
use App\Models\PromoPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('removes promos starting in the future when a brand is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(1);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    expect($brand->fresh()->promos()->count())->toBe(0);
    $this->assertSoftDeleted($promo);
});

it('removes promo line items on promos starting in the future when a brand is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->withLineItems(3)
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(1);
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(3);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    expect($brand->fresh()->promos()->first())->toBeNull();
    $this->assertSoftDeleted($promo);
});

it('does not remove promos when brand is not discontinued', function () {
    //## Arrange
    // create an active brand
    $brand = Brand::factory()->active()->create();

    // create a promo for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->promos()->count())->toBe(1);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    expect($brand->promos()->count())->toBe(1);
    $this->assertModelExists($promo);
});

it('does not remove promos starting in the past and ending in the future when a brand is discontinued', function () {
    //## Arrange
    // create an discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the past
    $skippedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInPast()->endsInFuture(), 'period')
        ->create();

    $deletedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->promos()->count())->toBe(2);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    // brand still has promos
    expect($brand->promos()->count())->toBe(1);
    expect($brand->promos()->first()->id)->toBe($skippedPromo->id);
    $this->assertSoftDeleted($deletedPromo);
});

it('does not remove promo line items on promos in the past and ending in the future when a brand is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $skippedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInPast()->endsInFuture(), 'period')
        ->withLineItems(3)
        ->create();

    $deletedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->withLineItems(3)
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(2);
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(3);
    expect($brand->refresh()->promos()->latest('id')->first()->lineItems()->count())->toBe(3);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    expect($brand->promos()->count())->toBe(1);

    $deletedPromo->lineItems->each(function (PromoLineItem $lineItem) {
        $this->assertSoftDeleted($lineItem);
    });

    $skippedPromo->lineItems->each(function (PromoLineItem $lineItem) {
        $this->assertModelExists($lineItem);
    });
});

it('does not remove expired promos when brand is discontinued', function () {
    //## Arrange
    // create an discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the past
    $skippedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->endsInPast(), 'period')
        ->create();

    $deletedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has promos
    expect($brand->promos()->count())->toBe(2);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    // brand still has promos
    expect($brand->promos()->count())->toBe(1);
    expect($brand->promos()->first()->id)->toBe($skippedPromo->id);
    $this->assertSoftDeleted($deletedPromo);
});

it('does not remove promo line items on expired promos in the past and ending in the future when a brand is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a promo for the brand in the future
    $skippedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->endsInPast(), 'period')
        ->withLineItems(3)
        ->create();

    $deletedPromo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->withLineItems(3)
        ->create();

    //## Assert
    // brand has promos
    expect($brand->refresh()->promos()->count())->toBe(2);
    expect($brand->refresh()->promos()->first()->lineItems()->count())->toBe(3);
    expect($brand->refresh()->promos()->latest('id')->first()->lineItems()->count())->toBe(3);

    //## Act
    // run the RemoveDiscontinuedBrandPromosAction
    app(RemoveDiscontinuedBrandPromosAction::class)->execute();

    //## Assert
    expect($brand->promos()->count())->toBe(1);

    $deletedPromo->lineItems->each(function (PromoLineItem $lineItem) {
        $this->assertSoftDeleted($lineItem);
    });

    $skippedPromo->lineItems->each(function (PromoLineItem $lineItem) {
        $this->assertModelExists($lineItem);
    });
});
