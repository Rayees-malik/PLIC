<?php

use App\Helpers\SignoffStateHelper;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('requires description', function () {
    $user = $this->signIn();
    $user->assign('admin');

    Vendor::factory()->create([
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->get(route('brands.create'))
        ->assertOk()
        ->assertSee('Edit Brand');

    $response = $this->post(route('brands.save'), [
        'description' => '',
    ]);

    $response
        ->assertOk()
        ->assertSee('Description is required');
});

it('requires french description', function () {
    $user = $this->signIn();
    $user->assign('admin');

    Vendor::factory()->create([
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->get(route('brands.create'))
        ->assertOk()
        ->assertSee('Edit Brand');

    $response = $this->post(route('brands.save'), [
        'description_fr' => '',
    ]);

    $response
        ->assertOk()
        ->assertSee('French description is required');
});
