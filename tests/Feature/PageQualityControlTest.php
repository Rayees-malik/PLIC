<?php

use App\Models\QualityControlRecord;
use App\Models\Warehouse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('shows list of all receiving records if user is member of qc admin role', function () {
    // Arrange
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();
    $qcAdminUser = User::factory()->create();

    $qcAdminUser->assign('qc-admin');

    QualityControlRecord::factory()
        ->for(Warehouse::factory())
        ->create([
            'po_number' => 'PO-123',
            'user_id' => $firstUser->id,
        ]);

    QualityControlRecord::factory()
        ->for(Warehouse::factory())
        ->create([
            'po_number' => 'PO-789',
            'user_id' => $secondUser->id,
        ]);

    loginAsUser($qcAdminUser);

    $this->getJson(route('qc.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonFragment(['po_number' => 'PO-123'])
        ->assertJsonFragment(['po_number' => 'PO-789']);
});

it('shows list of all receiving records if user has ability to view all records', function () {
    // Arrange
    $firstUser = User::factory()->create();
    $secondUser = User::factory()->create();
    $allowedUser = User::factory()->create();

    $allowedUser->allow('qc.view-all-qc-records');

    QualityControlRecord::factory()
        ->for(Warehouse::factory())
        ->create([
            'po_number' => 'PO-123',
            'user_id' => $firstUser->id,
        ]);

    QualityControlRecord::factory()
        ->for(Warehouse::factory())
        ->create([
            'po_number' => 'PO-789',
            'user_id' => $secondUser->id,
        ]);

    loginAsUser($allowedUser->refresh());

    $this->getJson(route('qc.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk()
        ->assertJsonFragment(['po_number' => 'PO-123'])
        ->assertJsonFragment(['po_number' => 'PO-789']);
});

it('has button to create new receiving record', function () {
    // Act & Assert
    loginAsUser()->assign('qc-technician-01');
    get(route('qc.index'))->assertOk()->assertSee('Create New');
});

it('shows a link to the labelling form if user can view records', function () {
    $user = User::factory()->create();
    $user->allow('view', QualityControlRecord::class);
    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    loginAsUser($user);

    $response = $this->getJson(route('qc.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk();

    $action = $response['data'][0]['action'];
    $this->assertStringContainsString(route('qc.labelling-form.show', $record->id), $action);
});

it('shows a link to the labelling form if user can edit records', function () {
    $user = User::factory()->create();
    $user->allow('edit', QualityControlRecord::class);
    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    loginAsUser($user);

    $response = $this->getJson(route('qc.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk();

    $action = $response['data'][0]['action'];
    $this->assertStringContainsString(route('qc.labelling-form.show', $record->id), $action);
});

it('shows the edit link in the table if user can edit records', function () {
    $user = User::factory()->create();
    $user->allow('edit', QualityControlRecord::class);
    $record = QualityControlRecord::factory()
        ->for($user)
        ->for(Warehouse::factory())
        ->create();

    loginAsUser($user);

    $response = $this->getJson(route('qc.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
        ->assertOk();

    $action = $response['data'][0]['action'];
    $this->assertStringContainsString(route('qc.edit', $record->id), $action);
});

it('names the download file correctly', function () {
    $this->signIn()->assign('admin');

    disableDownload();

    $response = $this->post(route('exports.export', ['name' => 'inventoryremovals']), [
        'start_date' => '2021-10-01',
        'end_date' => '2021-10-31',
    ]);

    $response->assertSuccessful();
    $response->assertHeader('Content-Disposition', 'attachment; filename="inventory_removals_2021-10-01_2021-10-31.xlsx"');
});
