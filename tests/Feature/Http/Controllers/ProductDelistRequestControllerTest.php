<?php

use App\Helpers\SignoffStateHelper;
use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(DatabaseTransactions::class);

it('redirects back if a pending delist request already exists', function () {
    $user = loginAsUser();
    $user->assign('admin');

    $delistRequest = ProductDelistRequest::factory()->create([
        'state' => SignoffStateHelper::PENDING,
    ]);

    Signoff::factory()
        ->submitted()
        ->for($delistRequest, 'initial')
        ->for($delistRequest->duplicate(), 'proposed')
        ->for($user)
        ->for(SignoffConfig::where('model', ProductDelistRequest::class)->first())
        ->create([
            'state' => SignoffStateHelper::PENDING,
        ]);

    get(route('productdelists.create', $delistRequest->product_id))->assertRedirect();
});

it('displays the create form if no delist requests exist', function () {
    $user = loginAsUser();
    $user->assign('admin');

    $product = Product::factory()->create();

    $response = get(route('productdelists.create', $product->id));

    $response->assertOk();
    $response->assertViewIs('productdelists.add');
});

it('displays the create form if no pending delist requests exist', function () {
    $user = loginAsUser();
    $user->assign('admin');

    $product = Product::factory()->create();
    $firstDelistRequest = ProductDelistRequest::factory()
        ->for($product)
        ->create();

    $secondDelistRequest = ProductDelistRequest::factory()
        ->for($product)
        ->create();

    $firstSignoff = Signoff::factory()
        ->submitted()
        ->for($firstDelistRequest, 'initial')
        ->for($firstDelistRequest->duplicate(), 'proposed')
        ->for($user)
        ->for(SignoffConfig::where('model', ProductDelistRequest::class)->first())
        ->create([
            'state' => SignoffStateHelper::APPROVED,
        ]);

    $secondSignoff = Signoff::factory()
        ->submitted()
        ->for($secondDelistRequest, 'initial')
        ->for($secondDelistRequest->duplicate(), 'proposed')
        ->for($user)
        ->for(SignoffConfig::where('model', ProductDelistRequest::class)->first())
        ->create([
            'state' => SignoffStateHelper::APPROVED,
        ]);

    $response = get(route('productdelists.create', $product->id));

    $response->assertOk();
    $response->assertViewIs('productdelists.add');
});

it('redirects if trying to create a duplicate delist request', function () {
    $user = loginAsUser();
    $user->assign('admin');

    $product = Product::factory()->create();
    $delistRequest = ProductDelistRequest::factory()
        ->for($product)
        ->create();

    $firstSignoff = Signoff::factory()
        ->submitted()
        ->for($delistRequest, 'initial')
        ->for($delistRequest->duplicate(), 'proposed')
        ->for($user)
        ->for(SignoffConfig::where('model', ProductDelistRequest::class)->first())
        ->approveUpToStep(2)
        ->create();

    $response = post(route('productdelists.store', [
        'productId' => $product->id,
        'reason' => 'foobar',
    ]));

    $response->assertRedirect();
});
