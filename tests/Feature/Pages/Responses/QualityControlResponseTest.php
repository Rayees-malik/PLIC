<?php

use App\Models\QualityControlRecord;
use App\Models\Warehouse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Silber\Bouncer\BouncerFacade as Bouncer;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('gives back successful response for qc index page', function () {
    $this->signIn();
    get(route('qc.index'))->assertOk();
});

it('gives back successful response for qc create receiving record page if user has permission', function () { // Act & Assert
// Arrange
    $user = User::factory()->create();
    Bouncer::allow($user)->toManage(QualityControlRecord::class);

    // Act & Assert
    loginAsUser($user);

    get(route('qc.create'))->assertOk();
});

it('forbids access to create page if user does not have permission', function () { // Act & Assert
// Arrange
    $user = User::factory()->create();
    Bouncer::forbid($user)->to(['create'], QualityControlRecord::class);

    // Act & Assert
    loginAsUser($user);
    get(route('qc.create'))->assertRedirect(route('home'));
});

it('gives back successful response for qc edit page', function () {
    // Arrange
    $user = User::factory()->create();
    $user->allow('update', QualityControlRecord::class);

    $qcRecord = QualityControlRecord::factory()->for($user)->create();

    // Act & Assert
    loginAsUser($user);
    get(route('qc.edit', $qcRecord->id))->assertOk();
});

it('gives back a successful response for the qc labelling form generated page', function () {
    $user = User::factory()->create();

    $user->allow('view', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    loginAsUser($user);
    get(route('qc.labelling-form.show', $record->id))->assertOk();
});

it('downloads the labelling form', function () {
    $user = User::factory()->create();

    $user->allow('view', QualityControlRecord::class);

    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    loginAsUser($user);

    $filename = "labelling-form_{$record->id}_{$record->product->stock_id}.pdf";

    disableDownload();
    get(route('qc.labelling-form.download', $record->id))
        ->assertSuccessful()
        ->assertHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
});
