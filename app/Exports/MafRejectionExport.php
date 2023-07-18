<?php

namespace App\Exports;

use App\Helpers\SignoffStateHelper;
use App\Models\SignoffResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MafRejectionExport extends BaseExport
{
    const MAF_DETAILS_HEADER = [
        'MAF ID',
        'Account Name and #',
        'Ship To Number',
        'Submitted By',
        'Original Submission Date',
        'Rejected At',
        'Rejected By',
        'Rejection Comment',
        'Retailer Invoice',
        'State',
    ];

    public const MAF_LINE_ITEMS_HEADER = [
        'MAF ID',
        'Account Name and #',
        'Brand',
        'Activity',
        'Promo Dates',
        'Cost',
        'MCB',
    ];

    private ?Spreadsheet $spreadsheet = null;

    private $data;

    private $details;

    private $lineItems;

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
        ]);

        $startDate = Carbon::parse($request->get('start_date'));

        $filename = 'maf_rejections_' . $startDate->toDateString() . '.xlsx';

        $this->spreadsheet = new Spreadsheet;

        return $this
            ->createSheets()
            ->setHeaders()
            ->retrieveData($startDate)
            ->transformData()
            ->insertData()
            ->applyStyles()
            ->downloadFile($this->spreadsheet, $filename);
    }

    public function getSpreadsheet()
    {
        return $this->spreadsheet;
    }

    private function createSheets()
    {
        $this->spreadsheet->getActiveSheet()->setTitle('Details');
        $this->spreadsheet->createSheet()->setTitle('Line Items');
        $this->spreadsheet->setActiveSheetIndexByName('Details');

        return $this;
    }

    private function setHeaders()
    {
        $this->spreadsheet
            ->getSheetByName('Details')
            ->fromArray(MafRejectionExport::MAF_DETAILS_HEADER, null, 'A1');

        $this->spreadsheet
            ->getSheetByName('Line Items')
            ->fromArray(MafRejectionExport::MAF_LINE_ITEMS_HEADER, null, 'A1');

        return $this;
    }

    private function retrieveData($startDate)
    {
        $this->data = DB::table('signoff_responses')
            ->select([
                'signoffs.initial_id as maf_id',
                'marketing_agreements.name as account_name',
                'submitted_by' => User::select('name')->withTrashed()->whereColumn('marketing_agreements.submitted_by', 'users.id'),
                'original_submitted_at' => SignoffResponse::select('created_at')->whereColumn('signoff_responses.signoff_id', 'signoffs.id')->oldest()->limit(1),
                'rejected_by' => User::select('name')->withTrashed()->whereColumn('signoff_responses.user_id', 'users.id'),
                'signoff_responses.comment as rejection_comment',
                'signoff_responses.created_at as rejected_at',
                'marketing_agreements.ship_to_number',
                'marketing_agreements.retailer_invoice',
                'signoffs.state as signoff_state',
                'marketing_agreement_line_items.id as line_item_id',
                'brands.name as brand_name',
                'marketing_agreement_line_items.activity',
                'marketing_agreement_line_items.promo_dates',
                'marketing_agreement_line_items.cost',
                'marketing_agreement_line_items.mcb_amount',
            ])
            ->join('signoffs', 'signoff_responses.signoff_id', '=', 'signoffs.id')
            ->join('marketing_agreements', 'signoffs.initial_id', '=', 'marketing_agreements.id')
            ->join('marketing_agreement_line_items', 'marketing_agreements.id', 'marketing_agreement_line_items.marketing_agreement_id')
            ->join('brands', 'marketing_agreement_line_items.brand_id', 'brands.id')
            ->where('signoff_responses.approved', false)
            ->where('signoff_responses.comment_only', 0)
            ->where('signoffs.created_at', '>=', $startDate)
            ->where('signoffs.initial_type', \App\Models\MarketingAgreement::class)
            ->get();

        return $this;
    }

    private function transformData()
    {
        $this->details = $this->data->map(function ($item, $key) {
            return [
                $item->maf_id,
                $item->account_name,
                $item->ship_to_number,
                $item->submitted_by,
                $item->original_submitted_at,
                $item->rejected_at,
                $item->rejected_by,
                $item->rejection_comment,
                $item->retailer_invoice,
                SignoffStateHelper::toString($item->signoff_state),
            ];
        })->unique()
            ->toArray();

        $this->lineItems = $this->data->map(function ($item, $key) {
            return [
                $item->maf_id,
                $item->account_name,
                $item->brand_name,
                $item->activity,
                $item->promo_dates,
                $item->cost,
                $item->mcb_amount,
            ];
        })->toArray();

        return $this;
    }

    private function insertData()
    {
        $this->spreadsheet->getSheetByName('Details')->fromArray($this->details, null, 'A2');
        $this->spreadsheet->getSheetByName('Line Items')->fromArray($this->lineItems, null, 'A2');

        return $this;
    }

    private function applyStyles()
    {
        $headerStyles = [
            'font' => [
                'bold' => true,
            ], 'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'ffc530',
                ],
            ],
        ];

        // Details sheet
        $detailsSheet = $this->spreadsheet->getSheetByName('Details');

        $detailsSheet->getStyle('A1:J1')->applyFromArray($headerStyles);

        foreach (range('A', $detailsSheet->getHighestColumn()) as $col) {
            $detailsSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Line Items sheet
        $lineItemsSheet = $this->spreadsheet->getSheetByName('Line Items');

        $lineItemsSheet->getStyle('A1:G1')->applyFromArray($headerStyles);

        foreach (range('A', $lineItemsSheet->getHighestColumn()) as $col) {
            $lineItemsSheet->getColumnDimension($col)->setAutoSize(true);

            if (in_array($col, ['F', 'G'])) {
                $lineItemsSheet
                    ->getStyle($col . '2:' . $col . $lineItemsSheet->getHighestDataRow($col))
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
            }
        }

        // Turn on auto-filter
        $detailsSheet->setAutoFilter($detailsSheet->calculateWorksheetDimension());
        $lineItemsSheet->setAutoFilter($lineItemsSheet->calculateWorksheetDimension());

        return $this;
    }
}
