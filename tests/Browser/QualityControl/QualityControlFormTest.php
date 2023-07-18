<?php

use App\Models\QualityControlRecord;
use App\Models\Warehouse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;

uses(DatabaseTruncation::class);

it('displays the number of units taken initially when editing', function () {
    $this->seed();

    $user = User::factory()->create();
    $user->assign('admin');

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create([
            'number_units_sent_for_testing' => 1000,
            'number_units_for_stability' => 2000,
            'number_units_retained' => 4000,
        ]);

    $this->browse(function (Browser $browser) use ($record, $user) {
        $browser->loginAs($user->id)
            ->visit('/qc')
            ->visitRoute('qc.edit', ['record' => $record])
            ->assertSeeIn('@units-taken', 7000);
    });
});

it('calculates the number of units taken when editing a record', function () {
    $this->seed();

    $user = User::factory()->create();
    $user->assign('admin');

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create([
            'number_units_sent_for_testing' => 1000,
            'number_units_for_stability' => 2000,
            'number_units_retained' => 4000,
        ]);

    $this->browse(function (Browser $browser) use ($record, $user) {
        $browser->loginAs($user->id)
            ->visit('/qc')
            ->visitRoute('qc.edit', ['record' => $record])
            ->pause(50)
            ->scrollIntoView('@sampling-report')
            ->assertValue('@units-sent-for-testing', 1000)
            ->assertValue('@units-for-stability', 2000)
            ->assertValue('@units-retained', 4000)
            ->assertSeeIn('@units-taken', 7000)
            ->screenshot('begin')
            ->pause(50)
            ->clear('@units-sent-for-testing')
            ->type('@units-sent-for-testing', 1)
            ->screenshot('first')
            ->pause(50)
            ->assertSeeIn('@units-taken', 6001)
            ->clear('@units-for-stability')
            ->type('@units-for-stability', 2)
            ->screenshot('second')
            ->pause(50)
            ->assertSeeIn('@units-taken', 4003)
            ->clear('@units-retained')
            ->type('@units-retained', 4)
            ->screenshot('third')
            ->pause(50)
            ->assertSeeIn('@units-taken', 7)
            ->screenshot('end');
    });
});

it('calculates the number of units taken when starting a new $user->idrecord', function () {
    $this->seed();

    $user = User::factory()->create();
    $user->assign('admin');

    $this->browse(function (Browser $browser) use ($user) {
        $browser->loginAs($user->id)
            ->visit('/qc')
            ->visitRoute('qc.create')
            ->pause(50)
            ->assertSeeIn('@units-taken', 0)
            ->pause(50)
            ->type('@units-sent-for-testing', 1000)
            ->screenshot('first')
            ->pause(50)
            ->assertSeeIn('@units-taken', 1000)
            ->type('@units-for-stability', 2000)
            ->screenshot('second')
            ->pause(50)
            ->assertSeeIn('@units-taken', 3000)
            ->type('@units-retained', 4000)
            ->screenshot('third')
            ->pause(50)
            ->assertSeeIn('@units-taken', 7000)
            ->screenshot('fourth');
    });
});
