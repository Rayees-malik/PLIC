<?php

use App\Models\InventoryRemoval;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PhpOffice\PhpSpreadsheet\IOFactory;

use function Pest\Laravel\get;

uses(DatabaseTransactions::class);

it('has a template', function () {
    $this->assertFileExists(resource_path('templates/printable_inventory_removal.xlsx'));
});

it('names the downloaded file', function () {
    disableDownload();

    $user = tap(loginAsUser(), fn ($user) => $user->assign('admin'));

    $inventoryRemoval = InventoryRemoval::factory()->withLineItems()->create();

    $signoff = Signoff::factory()
        ->for($user)
        ->for($inventoryRemoval, 'initial')
        ->for($inventoryRemoval->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', InventoryRemoval::class)->first())
        ->submitted()
        ->approved()
        ->create();

    get(route('exports.printinvremoval', ['id' => $inventoryRemoval->id]))
        ->assertSuccessful()
        ->assertHeader('Content-Disposition', 'attachment; filename="inventory-removal.xlsx"');
});

it('contains correctly named sheets', function () {
    $spreadsheet = IOFactory::load(resource_path('templates/printable_inventory_removal.xlsx'));

    expect(in_array('Line Items', $spreadsheet->getSheetNames()))->toBeTrue();
    expect(in_array('Signoff Responses', $spreadsheet->getSheetNames()))->toBeTrue();
});
