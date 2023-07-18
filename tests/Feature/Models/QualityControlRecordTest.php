<?php

use Altek\Accountant\Context;
use App\Models\Product;
use App\Models\QualityControlRecord;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use YlsIdeas\FeatureFlags\Facades\Features;

uses(DatabaseTransactions::class);

it('returns the correct number of units taken', function () {
    $qualityControlRecord = QualityControlRecord::factory()
        ->for(User::factory())
        ->for(Warehouse::factory())
        ->create([
            'number_units_sent_for_testing' => 1,
            'number_units_retained' => 2,
            'number_units_for_stability' => 4,
        ]);

    expect($qualityControlRecord->units_taken)->toBe(7);
});

it('has a relationship to a product', function () {
    // Arrange
    $qualityControlRecord = new QualityControlRecord;

    // Assert
    assertBelongsToUsing(Product::class, $qualityControlRecord->product(), 'product_id');
});

it('has a relationship to a vendor', function () {
    // Arrange
    $qualityControlRecord = new QualityControlRecord;

    // Assert
    assertBelongsToUsing(Vendor::class, $qualityControlRecord->vendor(), 'vendor_id');
});

it('has a relationship to a user', function () {
    // Arrange
    $qualityControlRecord = new QualityControlRecord;

    // Assert
    assertBelongsToUsing(User::class, $qualityControlRecord->user(), 'user_id');
});

it('has a relationship to a warehouse', function () {
    // Arrange
    $qualityControlRecord = new QualityControlRecord;

    // Assert
    assertBelongsToUsing(Warehouse::class, $qualityControlRecord->warehouse(), 'warehouse_id');
});

it('creates audit records', function () {
    Config::set('accountant.contexts', Context::WEB | Context::TEST);
    Features::fake(['remove-session-dependency' => true]); // TODO: need to finish removing session dependency

    $user = $this->signIn();
    $user->assign('admin');

    $qualityControlRecord = QualityControlRecord::factory()->for($user)->make();

    expect($qualityControlRecord->ledgers()->count())->toBe(0);

    $qualityControlRecord->save();

    expect($qualityControlRecord->ledgers()->count())->toBeGreaterThan(0);
});

it('has a relationship to a user that completed it', function () {
    // Arrange
    $qualityControlRecord = new QualityControlRecord;

    // Assert
    assertBelongsToUsing(User::class, $qualityControlRecord->completedBy(), 'completed_by');
});
