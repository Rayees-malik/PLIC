<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\Currency;
use App\Models\MarketingAgreement;
use Illuminate\Support\Str;

class MarketingAgreementChargeBackExport extends BaseExport
{
    const QUEBEC_PROVINCES = ['Quebec', 'Québec', 'QC'];

    const NON_TAXED_BRANDS = ['0735'];

    public function export($id, $brandId)
    {
        $brand = Brand::select('id', 'name', 'currency_id', 'brand_number')->findOrFail($brandId);
        $maf = MarketingAgreement::allStates()->with([
            'user',
            'as400Customer',
            'lineItems' => function ($query) use ($brandId) {
                $query->where('brand_id', $brandId);
            },
        ])->findOrFail($id);

        $usd = Currency::where('name', 'USD')->first();

        $spreadsheet = $this->loadFile('templates/maf_chargeback.xlsx');
        $sheet = $spreadsheet->getActiveSheet();

        $activities = [];
        $totalMCB = 0;
        foreach ($maf->lineItems as $lineItem) {
            $totalMCB += $lineItem->mcb_amount;
            $activities[] = ucfirst($lineItem->activity);
        }
        $activities = array_unique($activities);
        sort($activities);

        $isUSD = $brand->currency_id == $usd->id;
        $isQuebec = ! $isUSD && $maf->as400Customer && in_array($maf->as400Customer->province, $this::QUEBEC_PROVINCES);

        // Header Settings
        $sheet->setCellValue('I3', $brand->brand_number);
        $sheet->setCellValue('I4', $isUSD ? 'N' : 'Y');
        $sheet->setCellValue('I5', $isUSD ? 'Y' : 'N');
        $sheet->setCellValue('I6', $usd->exchange_rate);
        $sheet->setCellValue('I7', $isQuebec ? 'Y' : 'N');

        $taxRate = in_array($brand->brand_number, $this::NON_TAXED_BRANDS) ? 0 : $maf->tax_rate / 100;
        $sheet->setCellValue('I8', round($taxRate, 2));

        // Body
        $sheet->setCellValue('B16', $brand->name);
        $sheet->setCellValue('G16', $brand->brand_number);

        $sheet->setCellValue('B17', implode(', ', $activities));
        $sheet->setCellValue('B18', $maf->name);

        $sheet->setCellValue('D23', $totalMCB);

        $sheet->setCellValue('B33', $maf->user->name);
        $sheet->setCellValue('B34', $maf->user->email);

        $brandSnake = Str::snake($brand->name);

        return $this->downloadFile($spreadsheet, "maf_chargeback_{$brandSnake}.xlsx");
    }
}
