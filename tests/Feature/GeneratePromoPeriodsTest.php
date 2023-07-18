<?php

use App\Models\PromoPeriod;
use App\Models\Retailer;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;

uses(DatabaseTransactions::class);

it('can generate retailer promo periods for the next year', function () {
    $user = User::factory()->create();
    $user->assign('admin');

    $this->actingAs($user);

    $retailer = Retailer::factory()->create();

    $this->travelTo(Date::create(1998, 1, 1));

    $response = $this->post("/retailers/{$retailer->id}/promos/periods/generate");

    $this->assertEquals(12, $retailer->promoPeriods()->count());
});

it('can generate non retailer promo periods for the next year', function () {
    $user = User::factory()->create();
    $user->assign('admin');

    $this->actingAs($user);

    $this->travelTo(Date::create(1998, 1, 1));

    $response = $this->post('/promos/periods/generate');

    $this->travelBack();

    $this->assertEquals(12, PromoPeriod::whereYear('start_date', '1999')->whereNull('owner_id')->count());
});
