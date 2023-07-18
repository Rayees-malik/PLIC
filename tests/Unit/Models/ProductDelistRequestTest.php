<?php

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('updates the product status after signoff approval', function () {
    $product = Product::factory()->approved()->active()->create();

    $productDelistRequest = ProductDelistRequest::factory()->for($product)->create();

    expect($product->status)->toBe(StatusHelper::ACTIVE);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($productDelistRequest, 'initial')
        ->for($productDelistRequest->duplicate(), 'proposed')
        ->for(User::factory())
        ->for(SignoffConfig::where('model', ProductDelistRequest::class)->first())
        ->create([
            'state' => SignoffStateHelper::PENDING,
        ]);

    $productDelistRequest->onSignoffComplete($signoff);

    $product->refresh();

    expect($product->status)->toBe(StatusHelper::DISCONTINUED);
});
