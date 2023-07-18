<?php

namespace App\Exports;

use App\Models\Brand;
use App\Models\PricingAdjustment;
use App\Models\Product;
use Illuminate\Support\Str;

class PricingAdjustmentUploadExportWithMCB extends CSVExport
{
    public function export($id)
    {
        $paf = PricingAdjustment::allStates()->with([
            'lineItems',
            'user' => fn ($query) => $query->select('id', 'name'),
        ]
        )->findOrFail($id);

        $pafId = $paf->cloned_from_id ?? $paf->id;

        $data[] = [
            'Company #',
            'Vendor No',
            'Customer #',
            'Ship To Number',
            'Customer Conversion',
            'Stock Category',
            'Stock Number',
            'Chargeback code AA000',
            'Rebate Description',
            'Sales Price',
            'Unit Cost',
            'Sales Discount %',
            'Sales Discount 2 %',
            'Rebate Amount',
            'Rebate %',
            'Rebate Cal Code',
            'Multiple Rebates',
            'Calc On Credit?',
            'Authorized By',
            'Effective date',
            'Expiry Date',
            'Control Date Flag',
        ];

        foreach ($paf->lineItems as $lineItem) {
            $catCode = null;

            if ($lineItem->item instanceof Brand) {
                $catCode = $lineItem->item->category_code ?? (substr($lineItem->item->brand_code, 1) . '*');
            }

            $data[] = array_values([
                'A' => 'P',
                'B' => null,
                'C' => null,
                'D' => null,
                'E' => null,
                'F' => $catCode,
                'G' => $lineItem->item instanceof Product ? $lineItem->item->stock_id : null,
                'H' => null,
                'I' => Str::of($paf->start_date->format('Mj'))->append('-', $paf->end_date->format('Mj Y'), '(PAF', $pafId, ')')->upper()->toString(),
                'J' => 0,
                'K' => 0,
                'L' => 0,
                'M' => 0,
                'N' => $paf->dollar_mcb ? $lineItem->total_mcb : 0,
                'O' => ! $paf->dollar_mcb ? $lineItem->total_mcb : 0,
                'P' => $paf->dollar_mcb ? 'U' : 'P',
                'Q' => 'N',
                'R' => 'N',
                'S' => strtoupper($paf->user->name),
                'T' => $paf->start_date->format('Y-m-d'),
                'U' => $paf->end_date->format('Y-m-d'),
                'V' => 'O',
            ]);
        }

        return $this->downloadFile($data, 'paf_upload_with_mcb.csv');
    }
}
