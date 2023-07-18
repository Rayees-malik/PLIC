<?php

use App\Models\QualityControlRecord;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('includes the quality control form component on the create page', function () {
    $user = loginAsUser();
    $user->allow('create', QualityControlRecord::class);

    get(route('qc.create'))
        ->assertOk()
        ->assertSeeLivewire('quality-control-form');
});

it('includes the quality control form component on the edit page', function () {
    $user = loginAsUser();
    $user->allow('update', QualityControlRecord::class);

    $qcRecord = QualityControlRecord::factory()->for($user)->create();

    get(route('qc.edit', $qcRecord->id))
        ->assertOk()
        ->assertSeeLivewire('quality-control-form');
});

it('displays tag generation buttons if the record is saved ', function () {
    $user = loginAsUser();
    $user->allow('update', QualityControlRecord::class);

    $qcRecord = QualityControlRecord::factory()->for($user)->create();

    get(route('qc.edit', $qcRecord->id))
        ->assertOk()
        ->assertSeeText('Approval')
        ->assertSeeText('Rejection')
        ->assertSeeText('Pre-Released')
        ->assertSeeText('Destruction');
});
