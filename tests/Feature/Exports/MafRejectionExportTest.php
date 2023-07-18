<?php

use App\Exports\MafRejectionExport;
use App\Helpers\SignoffStateHelper;
use App\Models\MarketingAgreement;
use App\Models\Signoff;
use App\Models\SignoffConfig;
use App\Models\SignoffResponse;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('has a view', function () {
    expect(view()->exists('exports.forms.mafrejection'))->toBe(true);
});

it('must have rejected since date to export', function () {
    disableDownload();

    $this->signIn()->assign('admin');

    $this->post(route('exports.export', ['name' => 'mafrejection']), [
        'start_date' => '',
    ])->assertSessionHasErrors('start_date');
});

it('has the correct column headers on the details sheet', function () {
    disableDownload();

    $spreadsheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet();

    $headers = [
        'A' => 'MAF ID',
        'B' => 'Account Name and #',
        'C' => 'Ship To Number',
        'D' => 'Submitted By',
        'E' => 'Original Submission Date',
        'F' => 'Rejected At',
        'G' => 'Rejected By',
        'H' => 'Rejection Comment',
        'I' => 'Retailer Invoice',
        'J' => 'State',
    ];

    expect(
        $spreadsheet
            ->getSheetByName('Details')
            ->getHighestColumn()
    )->toBe(max(array_keys($headers)));

    expect($spreadsheet->getSheetByName('Details')->rangeToArray('A1:J1', null, true, true, false)[0])->toBe(array_values($headers));
});

it('has the correct column headers on the line items sheet', function () {
    disableDownload();

    $spreadsheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet();

    $headers = [
        'A' => 'MAF ID',
        'B' => 'Account Name and #',
        'C' => 'Brand',
        'D' => 'Activity',
        'E' => 'Promo Dates',
        'F' => 'Cost',
        'G' => 'MCB',
    ];

    $sheet = $spreadsheet->getSheetByName('Line Items');

    expect($sheet->getHighestColumn())->toBe(max(array_keys($headers)));

    expect($sheet->rangeToArray('A1:G1', null, true, true, false)[0])->toBe(array_values($headers));
});

it('has columns in correct order', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    $maf = MarketingAgreement::factory()->pending()->withLineItems()->create();

    $signoff = Signoff::factory()
        ->for($user)
        ->for($maf, 'initial')
        ->for($maf->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->rejectStep(1)
        ->create();

    $detailsSheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Details');

    $signoff = $signoff->fresh();

    expect(getCellValue(1, 2, $detailsSheet))->toBe($maf->id);
    expect(getCellValue(2, 2, $detailsSheet))->toBe($maf->name);
    expect(getCellValue(3, 2, $detailsSheet))->toBe($maf->ship_to_number);
    expect(getCellValue(4, 2, $detailsSheet))->toBe(User::withTrashed()->find($maf->submitted_by)->name);
    expect(getCellValue(5, 2, $detailsSheet))->toBe($signoff->responses->first()->created_at->toDateTimeString());
    expect(getCellValue(6, 2, $detailsSheet))->toEqual($signoff->responses->reject(fn ($response) => $response->comment_only == true)->first()->created_at);
    expect(getCellValue(7, 2, $detailsSheet))->toBe($signoff->responses()->with('user')->get()->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->user->name);
    expect(getCellValue(8, 2, $detailsSheet))->toBe($signoff->responses->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->comment);
    expect(getCellValue(9, 2, $detailsSheet))->toBe($maf->retailer_invoice);
    expect(getCellValue(10, 2, $detailsSheet))->toBe(SignoffStateHelper::toString($signoff->state));

    $lineItemsSheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Line Items');
    $lineItem = $maf->lineItems->first();

    expect(getCellValue(1, 2, $lineItemsSheet))->toBe($maf->id);
    expect(getCellValue(2, 2, $lineItemsSheet))->toBe($maf->name);
    expect(getCellValue(3, 2, $lineItemsSheet))->toBe($lineItem->brand->name);
    expect(getCellValue(4, 2, $lineItemsSheet))->toBe($lineItem->activity);
    expect(getCellValue(5, 2, $lineItemsSheet))->toBe($lineItem->promo_dates);
    expect(getCellValue(6, 2, $lineItemsSheet))->toEqual($lineItem->cost);
    expect(getCellValue(7, 2, $lineItemsSheet))->toEqual($lineItem->mcb_amount);
});

it('formats line item cost and mcb amout as numeric', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    $maf = MarketingAgreement::factory()->rejected()->withLineItems()->create();

    Signoff::factory()
        ->for($user)
        ->for($maf, 'initial')
        ->for($maf->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->has(SignoffResponse::factory()->rejected()->onStep(1), 'responses')
        ->create();

    $lineItem = $maf->lineItems->first();

    $sheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Line Items');

    expect(getCellValue(6, 2, $sheet))->toEqual($lineItem->cost);
    expect(getCellValue(7, 2, $sheet))->toEqual($lineItem->mcb_amount);
});

it('is named correctly', function () {
    disableDownload();

    $this->signIn()->assign('admin');

    $response = $this->post(route('exports.export', ['name' => 'mafrejection']), [
        'start_date' => '2021-10-01',
    ]);

    $response->assertHeader('Content-Disposition', 'attachment; filename="maf_rejections_2021-10-01.xlsx"');
});

it('contains correctly named sheets', function () {
    disableDownload();

    $spreadsheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet();

    expect(in_array('Details', $spreadsheet->getSheetNames()))->toBeTrue();
    expect(in_array('Line Items', $spreadsheet->getSheetNames()))->toBeTrue();
});

it('includes all rejected marketing agreements', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    $startDate = '2021-10-01 08:00:00';

    // should not see this
    $approvedMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems(4)->create();
    Signoff::factory()
        ->for($user)
        ->for($approvedMarketingAgreement, 'initial')
        ->for($approvedMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->approveToStep(1)
        ->create([
            'submitted_at' => $startDate,
        ]);

    // should see this
    $rejectedBySalesMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems(2)->create();
    Signoff::factory()
        ->for($user)
        ->for($rejectedBySalesMarketingAgreement, 'initial')
        ->for($rejectedBySalesMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->submitted('2021-10-02 09:10:15')
        ->rejectStep(1, '2021-10-03 10:20:25')
        ->create([
            'submitted_at' => $startDate,
        ]);

    // should see this
    $rejectedByAccountingMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems(6)->create();
    Signoff::factory()
        ->for($user)
        ->for($rejectedByAccountingMarketingAgreement, 'initial')
        ->for($rejectedByAccountingMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->submitted('2021-10-04 11:30:35')
        ->approveStep(1, '2021-10-05 12:40:45')
        ->rejectStep(2, '2021-10-06 13:50:55')
        ->create([
            'submitted_at' => $startDate,
        ]);

    $sheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Details');

    expect($sheet->getHighestRow())->toBe(3);
    expect(getCellValue(1, 2, $sheet))->toBe($rejectedBySalesMarketingAgreement->id);
    expect(getCellValue(1, 3, $sheet))->toBe($rejectedByAccountingMarketingAgreement->id);
    expect(getCellValue(1, 4, $sheet))->toBeNull();
});

it('includes marketing agreements line items', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');
    $startDate = now();

    // should not see this
    $approvedMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems()->create();
    $approvedSignoff = Signoff::factory()
        ->for($user)
        ->for($approvedMarketingAgreement, 'initial')
        ->for($approvedMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->approveToStep(1)
        ->create();

    // should see this
    $rejectedBySalesMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems()->create();
    Signoff::factory()
        ->for($user)
        ->for($rejectedBySalesMarketingAgreement, 'initial')
        ->for($rejectedBySalesMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->rejectStep(1)
        ->create();

    // should see this
    $rejectedByAccountingMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems()->create();
    Signoff::factory()
        ->for($user)
        ->for($rejectedByAccountingMarketingAgreement, 'initial')
        ->for($rejectedByAccountingMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->rejectStep(2)
        ->create();

    $sheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Line Items');

    $range = $sheet->rangeToArray('A2:G3', null, true, true, false);

    expect($sheet->getHighestRow())->toBe(3);

    expect($range)->toEqual([
        [
            $rejectedBySalesMarketingAgreement->id,
            $rejectedBySalesMarketingAgreement->name,
            $rejectedBySalesMarketingAgreement->lineItems[0]->brand->name,
            $rejectedBySalesMarketingAgreement->lineItems[0]->activity,
            $rejectedBySalesMarketingAgreement->lineItems[0]->promo_dates,
            $rejectedBySalesMarketingAgreement->lineItems[0]->cost,
            $rejectedBySalesMarketingAgreement->lineItems[0]->mcb_amount,
        ], [
            $rejectedByAccountingMarketingAgreement->id,
            $rejectedByAccountingMarketingAgreement->name,
            $rejectedByAccountingMarketingAgreement->lineItems[0]->brand->name,
            $rejectedByAccountingMarketingAgreement->lineItems[0]->activity,
            $rejectedByAccountingMarketingAgreement->lineItems[0]->promo_dates,
            $rejectedByAccountingMarketingAgreement->lineItems[0]->cost,
            $rejectedByAccountingMarketingAgreement->lineItems[0]->mcb_amount,
        ],
    ]);
});

it('includes only rejections after provided date', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');
    $this->travel(-2)->days();

    // should not see this
    $olderMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems()->create();
    $olderSignoff = Signoff::factory()
        ->for($user)
        ->for($olderMarketingAgreement, 'initial')
        ->for($olderMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->rejectStep(1)
        ->create();

    $this->travel(1)->days();

    // should see this
    $newerMarketingAgreement = MarketingAgreement::factory()->pending()->withLineItems()->create();
    $newerSignoff = Signoff::factory()
        ->for($user)
        ->for($newerMarketingAgreement, 'initial')
        ->for($newerMarketingAgreement->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->rejectStep(1)
        ->create();

    $this->travelBack();

    $detailsSheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => now()->subday(1)->format('Y-m-d'),
        ]
    )->getSpreadsheet()->getSheetByName('Details');

    expect($detailsSheet->getHighestRow())->toBe(2);
    expect(getCellValue(1, 2, $detailsSheet))->toBe($newerMarketingAgreement->id);
    expect(getCellValue(1, 3, $detailsSheet))->toBeNull();

    $lineItemsSheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => now()->subday(1)->format('Y-m-d'),
        ]
    )->getSpreadsheet()->getSheetByName('Line Items');
    $lineItem = $newerMarketingAgreement->lineItems[0];

    expect($lineItemsSheet->getHighestRow())->toBe(2);
    expect(getCellValue(1, 2, $lineItemsSheet))->toBe($lineItem->marketing_agreement_id);
    expect(getCellValue(2, 2, $lineItemsSheet))->toBe($newerMarketingAgreement->name);
    expect(getCellValue(3, 2, $lineItemsSheet))->toBe($lineItem->brand->name);
    expect(getCellValue(4, 2, $lineItemsSheet))->toBe($lineItem->activity);
    expect(getCellValue(5, 2, $lineItemsSheet))->toBe($lineItem->promo_dates);
    expect(getCellValue(6, 2, $lineItemsSheet))->toEqual($lineItem->cost);
    expect(getCellValue(7, 2, $lineItemsSheet))->toEqual($lineItem->mcb_amount);
});

it('creates a row for every signoff response on the details sheet', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    $maf = MarketingAgreement::factory()->pending()->withLineItems()->create();
    $signoff = Signoff::factory()
        ->for($user)
        ->for($maf, 'initial')
        ->for($maf->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->submitted('2021-10-02 08:10:15')
        ->rejectStep(1, '2021-10-03 09:20:25')
        ->submitted('2021-10-04 10:30:35')
        ->rejectStep(1, '2021-10-05 11:40:45')
        ->create();

    $signoff = $signoff->fresh('responses');

    $sheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ])->getSpreadsheet()->getSheetByName('Details');

    expect($sheet->getHighestRow())->toBe(3);

    expect($sheet->rangeToArray('A2:J3', null, true, true, false))
        ->toEqual([
            [
                $maf->id,
                $maf->name,
                $maf->ship_to_number,
                User::withTrashed()->find($maf->submitted_by)->name,
                $signoff->responses->first()->created_at->toDateTimeString(),
                $signoff->responses->reject(fn ($response) => $response->comment_only == true)->first()->created_at->toDateTimeString(),
                $signoff->responses()->with('user')->get()->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->user->name,
                $signoff->responses->reject(fn ($response) => $response->comment_only == true || $response->approved)->first()->comment,
                $maf->retailer_invoice,
                SignoffStateHelper::toString($signoff->state),
            ],
            [
                $maf->id,
                $maf->name,
                $maf->ship_to_number,
                User::withTrashed()->find($maf->submitted_by)->name,
                $signoff->responses->first()->created_at->toDateTimeString(),
                $signoff->responses->reject(fn ($response) => $response->comment_only == true)->last()->created_at->toDateTimeString(),
                $signoff->responses()->with('user')->get()->reject(fn ($response) => $response->comment_only == true || $response->approved)->last()->user->name,
                $signoff->responses->reject(fn ($response) => $response->comment_only == true || $response->approved)->last()->comment,
                $maf->retailer_invoice,
                SignoffStateHelper::toString($signoff->state),
            ],
        ]);
});

it('has the date of the original submission on the details sheet', function () {
    disableDownload();

    $user = $this->signIn()->assign('admin');

    $maf = MarketingAgreement::factory()->pending()->withLineItems()->create();
    $signoff = Signoff::factory()
        ->for($user)
        ->for($maf, 'initial')
        ->for($maf->duplicate(), 'proposed')
        ->for(SignoffConfig::where('model', MarketingAgreement::class)->first())
        ->submitted('2021-10-02 08:10:15')
        ->rejectStep(1, '2021-10-03 09:20:25')
        ->submitted('2021-10-04 10:30:35')
        ->rejectStep(1, '2021-10-05 11:40:45')
        ->create()->fresh();

    $sheet = createExport(
        routeName: 'mafrejection',
        exportClass: MafRejectionExport::class,
        params: [
            'start_date' => '2021-10-01',
        ]
    )->getSpreadsheet()->getSheetByName('Details');

    expect(getCellValue(5, 2, $sheet))->toBe($signoff->responses->first()->created_at->toDateTimeString());
});
