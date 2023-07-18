<?php

use App\Actions\Database\RemoveDiscontinuedBrandCaseStackDealsAction;
use App\Models\Brand;
use App\Models\CaseStackDeal;
use App\Models\PromoPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('removes case stack deals starting in the future when a brand is discontinued', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a case stack deal the brand in the future
    $caseStackDeal = CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(1);

    //## Act
    // run the RemoveDiscontinuedBrandCaseStackDealsAction
    app(RemoveDiscontinuedBrandCaseStackDealsAction::class)->execute();

    //## Assert
    expect($brand->fresh()->caseStackDeals()->count())->toBe(0);
});

it('does not remove case stack deals when brand is not discontinued', function () {
    //## Arrange
    // create an active brand
    $brand = Brand::factory()->active()->create();

    // create a case stack deal the brand in the future
    CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(1);

    //## Act
    // run the RemoveDiscontinuedBrandCaseStackDealsAction
    app(RemoveDiscontinuedBrandCaseStackDealsAction::class)->execute();

    //## Assert
    expect($brand->caseStackDeals()->count())->toBe(1);
});

it('does not remove case stack deals starting in the past and ending in the future when a brand is discontinued', function () {
    //## Arrange
    // create an discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a case stack deal the brand in the past
    $skippedCaseStackDeal = CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInPast()->endsInFuture(), 'period')
        ->create();

    $deletedCaseStackDeal = CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(2);

    //## Act
    // run the RemoveDiscontinuedBrandCaseStackDealsAction
    app(RemoveDiscontinuedBrandCaseStackDealsAction::class)->execute();

    //## Assert
    // brand still has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(1);
    expect($brand->caseStackDeals()->first()->id)->toBe($skippedCaseStackDeal->id);
    $this->assertSoftDeleted($deletedCaseStackDeal);
});

it('does not remove expired case stack deals when brand is discontinued', function () {
    //## Arrange
    // create an discontinued brand
    $brand = Brand::factory()->discontinued()->create();

    // create a case stack deal the brand in the past
    $skippedCaseStackDeal = CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->endsInPast(), 'period')
        ->create();

    $deletedCaseStackDeal = CaseStackDeal::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    //## Assert
    // brand has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(2);

    //## Act
    // run the RemoveDiscontinuedBrandCaseStackDealsAction
    app(RemoveDiscontinuedBrandCaseStackDealsAction::class)->execute();

    //## Assert
    // brand still has case stack deals
    expect($brand->caseStackDeals()->count())->toBe(1);
    expect($brand->caseStackDeals()->first()->id)->toBe($skippedCaseStackDeal->id);
    $this->assertSoftDeleted($deletedCaseStackDeal);
});
