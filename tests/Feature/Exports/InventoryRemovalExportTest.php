<?php

use App\Exports\InventoryRemovalExport;
use App\Helpers\SignoffStateHelper;
use App\Models\InventoryRemoval;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\Warehouse;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('has a view', function () {
    expect(view()->exists('exports.forms.inventoryremovals'))->toBe(true);
});

it('must have start and end dates to export', function () {
    disableDownload();

    $this->signIn()->assign('admin');

    $this->post(route('exports.export', ['name' => 'inventoryremovals']), [
        'start_date' => '',
        'end_date' => '',
    ])->assertSessionHasErrors(['start_date', 'end_date']);
});

it('has the correct column headers', function () {
    disableDownload();

    $spreadsheet = createExport(
        routeName: 'inventoryremovals',
        exportClass: InventoryRemovalExport::class,
        params: [
            'start_date' => '2021-10-01',
            'end_date' => '2021-10-31',
        ])->getSpreadsheet();

    $headers = [
        'A' => 'Signoff ID',
        'B' => 'Signoff Step Name',
        'C' => 'Submitted By',
        'D' => 'Submitted At',
        'E' => 'Product Name',
        'F' => 'Stock ID',
        'G' => 'Size',
        'H' => 'Brand Name',
        'I' => 'Quantity',
        'J' => 'Cost',
        'K' => 'Ext Cost',
        'L' => 'Full MCB',
        'M' => 'Consignment',
        'N' => 'Expiry',
        'O' => 'Reserve',
        'P' => 'Comment',
        'Q' => 'Warehouse Number',
        'R' => 'Warehouse Name',
        'S' => 'Completed',
        'T' => 'Completed At',
        'U' => 'Approved',
        'V' => 'Final Approval At',
    ];

    expect(
        $spreadsheet
            ->getSheetByName('Inventory Removals')
            ->getHighestColumn()
    )->toBe(max(array_keys($headers)));

    expect($spreadsheet->getSheetByName('Inventory Removals')->rangeToArray('A1:V1', null, true, true, false)[0])->toBe(array_values($headers));
});

it('has columns in correct order', function () {
    disableDownload();

    $this->travelTo(Carbon::parse('2021-10-10'));

    $user = $this->signIn()->assign('admin');

    $inventoryRemoval = InventoryRemoval::factory()
        ->for($user)
        ->pending()
        ->withLineItems(1, Warehouse::factory()->create()->number)
        ->create();

    $signoff = Signoff::factory()
        ->for($user)
        ->for($inventoryRemoval, 'initial')
        ->for($inventoryRemoval->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', InventoryRemoval::class)->first())
        ->submitted()
        ->create([
            'step' => 3,
        ]);

    $detailsSheet = createExport(
        routeName: 'inventoryremovals',
        exportClass: InventoryRemovalExport::class,
        params: [
            'start_date' => '2021-10-01',
            'end_date' => '2021-10-31',
        ])->getSpreadsheet()->getSheetByName('Inventory Removals');

    $signoff = $signoff->fresh();

    $this->travelBack();

    expect(getCellValue(1, 2, $detailsSheet))->toBe($signoff->id);
    expect(getCellValue(2, 2, $detailsSheet))->toBe($signoff->stepConfig->name);
    expect(getCellValue(3, 2, $detailsSheet))->toBe(User::withTrashed()->find($inventoryRemoval->submitted_by)->name);
    expect(getCellValue(4, 2, $detailsSheet))->toBe($signoff->submitted_at->toDateTimeString());
    // expect(getCellValue(6, 2, $detailsSheet))->toEqual($signoff->responses->reject(fn ($response) => $response->comment_only == true)->first()->created_at);
    // expect(getCellValue(7, 2, $detailsSheet))->toBe($signoff->responses()->with('user')->get()->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->user->name);
    // expect(getCellValue(8, 2, $detailsSheet))->toBe($signoff->responses->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->comment);
    // expect(getCellValue(9, 2, $detailsSheet))->toBe($maf->retailer_invoice);
    // expect(getCellValue(10, 2, $detailsSheet))->toBe(SignoffStateHelper::toString($signoff->state));
});

it('names the download file correctly', function () {
    disableDownload();

    $this->signIn()->assign('admin');

    $response = $this->post(route('exports.export', ['name' => 'inventoryremovals']), [
        'start_date' => '2021-10-01',
        'end_date' => '2021-10-31',
    ]);

    $response->assertSuccessful();
    $response->assertHeader('Content-Disposition', 'attachment; filename="inventory_removals_2021-10-01_2021-10-31.xlsx"');
});

it('contains correctly named sheets', function () {
    disableDownload();

    $spreadsheet = createExport(
        routeName: 'inventoryremovals',
        exportClass: InventoryRemovalExport::class,
        params: [
            'start_date' => '2021-10-01',
            'end_date' => '2021-10-31',
        ])->getSpreadsheet();

    expect(in_array('Inventory Removals', $spreadsheet->getSheetNames()))->toBeTrue();
});

it('only includes inventory removals submitted between the start and end dates', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    // should not see this
    $olderInventoryRemoval = InventoryRemoval::factory()
        ->for($user)
        ->pending()
        ->withLineItems(warehouseNumber: '04')
        ->create();

    $olderSignoff = Signoff::factory()
        ->for($user)
        ->for($olderInventoryRemoval, 'initial')
        ->for($olderInventoryRemoval->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', InventoryRemoval::class)->first())
        ->submitted('2022-01-31')
        ->create([
            'step' => 3,
        ]);

    // should see this
    $newerInventoryRemoval = InventoryRemoval::factory()
        ->for($user)
        ->pending()
        ->withLineItems(warehouseNumber: '04')
        ->create();

    $newerSignoff = Signoff::factory()
        ->for($user)
        ->for($newerInventoryRemoval, 'initial')
        ->for($newerInventoryRemoval->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', InventoryRemoval::class)->first())
        ->submitted('2022-02-05')
        ->create([
            'step' => 3,
        ]);

    $sheet = createExport(
        routeName: 'inventoryremovals',
        exportClass: InventoryRemovalExport::class,
        params: [
            'start_date' => '2022-02-01',
            'end_date' => '2022-02-28',
        ])->getSpreadsheet()->getSheetByName('Inventory Removals');

    expect($sheet->getHighestRow())->toBe(2);
    expect(getCellValue(1, 2, $sheet))->toBe($newerSignoff->id);
    expect(getCellValue(1, 3, $sheet))->toBeNull();
});
