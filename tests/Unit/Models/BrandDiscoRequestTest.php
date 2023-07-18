<?php

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\Brand;
use App\Models\BrandDiscoRequest;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('updates the brand status after signoff approval', function () {
    $product = Brand::factory()->approved()->active()->create();

    $brandDiscoRequest = BrandDiscoRequest::factory()->for($product)->create();

    expect($product->status)->toBe(StatusHelper::ACTIVE);

    $signoff = Signoff::factory()
        ->submitted()
        ->for($brandDiscoRequest, 'initial')
        ->for($brandDiscoRequest->duplicate(), 'proposed')
        ->for(User::factory())
        ->for(SignoffConfig::where('model', BrandDiscoRequest::class)->first())
        ->create([
            'state' => SignoffStateHelper::PENDING,
        ]);

    $brandDiscoRequest->onSignoffComplete($signoff);

    $product->refresh();

    expect($product->status)->toBe(StatusHelper::DISCONTINUED);
});
