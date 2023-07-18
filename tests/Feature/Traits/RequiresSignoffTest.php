<?php

use App\Models\Product;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('is not a new submission if id is not set', function () {
    $product = Product::factory()->catalogueActive()->make();
    expect($product->isNewSubmission)->toBe(true);
});

it('is not a new submission if signoff is not attached', function () {
    $product = Product::factory()->catalogueActive()->create();
    expect($product->isNewSubmission)->toBe(false);
});

it('returns new submission field if signoff is attached and new submission field is set', function () {
    $product = Product::factory()->approved()->catalogueActive()->create();

    $signoff = Signoff::factory()
        ->for(User::factory())
        ->for($product, 'initial')
        ->for($proposedProduct = $product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->approveStep(1)
        ->create([
            'new_submission' => false,
            'step' => 1,
        ]);

    expect($proposedProduct->signoff)->toBeInstanceOf(Signoff::class);
    expect($proposedProduct->isNewSubmission)->toBeFalsy();

    $signoff = Signoff::factory()
        ->for(User::factory())
        ->for($product, 'initial')
        ->for($proposedProduct = $product->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', Product::class)->first())
        ->approveStep(1)
        ->create([
            'new_submission' => true,
            'step' => 1,
        ]);

    expect($proposedProduct->signoff)->toBeInstanceOf(Signoff::class);
    expect($proposedProduct->isNewSubmission)->toBeTruthy();
});
