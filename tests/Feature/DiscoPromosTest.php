<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('disco promo update ability must exist', function () {
    $this->assertDatabaseHas('abilities', [
        'name' => 'promo.update.discos',
    ]);
});

it('old promo discos ability is renamed', function () {
    $this->assertDatabaseMissing('abilities', [
        'name' => 'promo.discos',
    ]);
});

it('can access edit view with update ability', function () {
    $user = User::factory()->create();
    $user->allow('promo.update.discos');
    $this->actingAs($user);

    $response = $this->get('/promos/disco/edit');

    $response->assertViewIs('discopromos.edit');
});

it('can access index view with view ability', function () {
    $user = User::factory()->create();
    $user->allow('promo.view.discos');

    $this->actingAs($user);

    $response = $this->get('/promos/disco');

    $response->assertViewIs('discopromos.view');
});
