<?php

use App\Actions\Database\DeleteDraftSignoffsAction;
use App\Models\Brand;
use App\Models\MarketingAgreement;
use App\Models\PricingAdjustment;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\Vendor;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;

uses(DatabaseTransactions::class);

it('soft deletes draft signoffs after default 30 days of inactivity ', function ($model) {
    // Arrange
    // create a user
    $user = User::factory()->create();
    $user->assign('admin');

    // create a sample products
    $modelForOutdatedSignoff = $model::factory()->approved()->create();
    $modelForRecentSignoff = $model::factory()->approved()->create();

    // create an archived signoff updated 31 days ago
    $outdatedSignoff = Signoff::factory()
        ->for($user)
        ->for($modelForOutdatedSignoff, 'initial')
        ->for($modelForOutdatedSignoff->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', $model)->first())
        ->inProgress()
        ->create([
            'updated_at' => now()->subDays(31),
        ]);

    // create an signoff updated 5 days ago
    $recentSignoff = Signoff::factory()->for($user)
        ->for($modelForRecentSignoff, 'initial')
        ->for($modelForRecentSignoff->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', $model)->first())
        ->inProgress()
        ->create([
            'updated_at' => now()->subDays(5),
        ]);

    // Act
    // run the action
    app(DeleteDraftSignoffsAction::class)->execute();

    // Assert
    // confirm that the outdated signoff is soft deleted
    $this->assertSoftDeleted($outdatedSignoff);
    $this->assertNotSoftDeleted($recentSignoff);

    // confirm the user does not see the outdated signoff
    expect(Signoff::inProgress()->count())->toBe(1);

    // confirm user does not see the outdated signoff in the datatable
    $this->actingAs($user)
        ->getJson(
            route('user.submissions', ['filter' => 'draft']),
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        )->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'description' => $recentSignoff->proposed->displayName,
        ]);
})->with([
    Brand::class,
    MarketingAgreement::class,
    PricingAdjustment::class,
    Product::class,
    Promo::class,
    Vendor::class,
]);

it('soft deletes draft signoffs after configured delay days of inactivity ', function ($model) {
    // Arrange
    // create a user
    $user = User::factory()->create();
    $user->assign('admin');

    Config::set('plic.outdated_signoff_cleanup.draft_delay_days', 5);

    // create a sample products
    $modelForOutdatedSignoff = $model::factory()->approved()->create();
    $modelForRecentSignoff = $model::factory()->approved()->create();

    // create an archived signoff updated 6 days ago
    $outdatedSignoff = Signoff::factory()
        ->for($user)
        ->for($modelForOutdatedSignoff, 'initial')
        ->for($modelForOutdatedSignoff->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', $model)->first())
        ->inProgress()
        ->create([
            'updated_at' => now()->subDays(6),
        ]);

    // create an signoff updated 5 days ago
    $recentSignoff = Signoff::factory()->for($user)
        ->for($modelForRecentSignoff, 'initial')
        ->for($modelForRecentSignoff->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', $model)->first())
        ->inProgress()
        ->create([
            'updated_at' => now()->subDays(5),
        ]);

    // Act
    // run the action
    app(DeleteDraftSignoffsAction::class)->execute();

    // Assert
    // confirm that the outdated signoff is soft deleted
    $this->assertSoftDeleted($outdatedSignoff);
    $this->assertNotSoftDeleted($recentSignoff);

    // confirm the user does not see the outdated signoff
    expect(Signoff::inProgress()->count())->toBe(1);

    // confirm user does not see the outdated signoff in the datatable
    $this->actingAs($user)
        ->getJson(
            route('user.submissions', ['filter' => 'draft']),
            ['HTTP_X-Requested-With' => 'XMLHttpRequest']
        )->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment([
            'description' => $recentSignoff->proposed->displayName,
        ]);
})->with([
    Brand::class,
    MarketingAgreement::class,
    PricingAdjustment::class,
    Product::class,
    Promo::class,
    Vendor::class,
]);
