<?php

namespace App\Exports;

use App\Models\MarketingAgreement;
use Carbon\Carbon;

class MarketingAgreementJournalExport extends BaseExport
{
    const OA_BRANDS = ['0339', '1339', '0184'];

    const SPLIT_OA_BRANDS = ['0735'];

    const QUEBEC_PROVINCES = ['Quebec', 'Québec', 'QC'];

    const QUEBEC_GST = 0.05;

    const QUEBEC_QST = 0.09975;

    public function export($id)
    {
        $maf = MarketingAgreement::allStates()->with([
            'user',
            'as400Customer' => function ($query) {
                $query->with('customerGLAccount');
            },
            'lineItems' => function ($query) {
                $query->with([
                    'brand' => function ($query) {
                        $query->select('id', 'name', 'brand_number');
                    },
                ]);
            },
        ])->findOrFail($id);

        $spreadsheet = $this->loadFile('templates/maf_journal.xlsx');
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('D3', "Journal Entry #{$maf->identifier}");
        $sheet->setCellValue('D4', "Invoice #{$maf->retailer_invoice}");
        $sheet->setCellValue('D5', $maf->created_at->year);

        // Line Items
        $totalCost = 0;
        $totalMCB = 0;
        $totalOA = 0;

        $brands = [];
        $activities = [];

        foreach ($maf->lineItems as $lineItem) {
            $totalCost += $lineItem->cost;

            if ($maf->mcb_amount == 0 && in_array($lineItem->brand->brand_number, $this::OA_BRANDS)) {
                $totalOA += $lineItem->cost;
            } elseif (in_array($lineItem->brand->brand_number, $this::SPLIT_OA_BRANDS)) {
                $totalMCB += round($lineItem->cost / 2, 2);
                $totalOA += round($lineItem->cost / 2, 2);
            } else {
                $totalMCB += round($lineItem->mcb_amount, 2);
            }

            $activities[] = ucfirst($lineItem->activity);
            $brands[] = $lineItem->brand->name;
        }

        $isQuebec = $maf->as400Customer && in_array($maf->as400Customer->province, $this::QUEBEC_PROVINCES);
        $glAccount = $maf->as400Customer->glAccount;

        $taxRate = is_nan($maf->tax_rate) ? 0 : $maf->tax_rate;
        $tax = $isQuebec ? $totalCost * $this::QUEBEC_GST : $totalCost * ($taxRate / 100);
        $qst = $isQuebec ? $totalCost * $this::QUEBEC_QST : 0;

        $sheet->setCellValue('E8', $totalMCB);

        $sheet->setCellValue('C9', "2135.{$glAccount}");
        $sheet->setCellValue('D9', "O/A - {$maf->name}");
        $sheet->setCellValue('E9', $totalOA);

        $sheet->setCellValue('E10', $tax, 2);

        $line = 11;
        if ($isQuebec) {
            $sheet->setCellValue("C{$line}", '2179.00');
            $sheet->setCellValue("D{$line}", 'QST');
            $sheet->setCellValue("E{$line}", $qst, 2);

            $line++;
        }

        $sheet->setCellValue("C{$line}", "1131.{$glAccount}");
        $sheet->setCellValue("D{$line}", "DM Rec - {$maf->name}");
        $sheet->setCellValue("F{$line}", $totalMCB + $totalOA + $tax + $qst);
        $line++;

        $sheet->setCellValue("C{$line}", "1131.{$glAccount}");
        $sheet->setCellValue("D{$line}", "DM Rec - {$maf->name}");
        $sheet->setCellValue("E{$line}", $totalMCB + $totalOA + $tax + $qst);
        $line++;

        if ($isQuebec) {
            $sheet->setCellValue("C{$line}", '2120.00');
            $sheet->setCellValue("D{$line}", 'GST');
            $sheet->setCellValue("F{$line}", $tax);
            $line++;

            $sheet->setCellValue("C{$line}", '2179.00');
            $sheet->setCellValue("D{$line}", 'QST');
            $sheet->setCellValue("F{$line}", $qst);
            $line++;

            $sheet->setCellValue("C{$line}", '1132.10');
            $sheet->setCellValue("D{$line}", 'MCB to A/R');
            $sheet->setCellValue("F{$line}", $totalMCB + $totalOA);
        } else {
            $sheet->setCellValue("F{$line}", $tax);
            $line++;

            $sheet->setCellValue("F{$line}", $totalMCB + $totalOA);
        }

        // Reason Box
        $accountOther = $maf->account == 'Other' ? '' : " (#{$maf->account})";
        $sheet->setCellValue('B42', ucfirst($maf->name) . $accountOther);
        $activities = array_unique($activities);
        sort($activities);
        $brands = array_unique($brands);
        sort($brands);

        $sheet->setCellValue('B43', implode(', ', $activities));
        $sheet->setCellValue('B44', implode(', ', $brands));

        // Footer
        $sheet->setCellValue('C46', $maf->user->name);
        $sheet->setCellValue('E46', Carbon::now()->toFormattedDateString());

        return $this->downloadFile($spreadsheet, 'maf_journal.xlsx');
    }
}
