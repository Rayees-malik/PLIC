<?php

namespace App\Exports;

use App\Models\PricingAdjustment;
use App\Models\Product;

class PricingAdjustmentUploadExport extends CSVExport
{
    public function export($id)
    {
        $paf = PricingAdjustment::allStates()->with('lineItems')->findOrFail($id);

        $data = [['CO', 'DESC', 'STOCK #', 'EXTRA', 'PRICE', 'EFFECTIVE', 'EXPIRY', 'DISCOUNT']];
        foreach ($paf->lineItems as $lineItem) {
            $stockNum = '';
            if ($lineItem->item instanceof Product) {
                $stockNum = $lineItem->item->stock_id;
            } else {
                $stockNum = $lineItem->item->category_code ?? (substr($lineItem->item->brand_code, 1) . '*');
            }

            $data[] = [
                'P',
                null,
                $stockNum,
                null,
                $paf->dollar_discount ? $lineItem->total_discount : '',
                $paf->start_date->format('Y-m-d'),
                $paf->end_date->format('Y-m-d'),
                $paf->dollar_discount ? 0 : $lineItem->total_discount,
            ];
        }

        return $this->downloadFile($data, 'paf_upload.csv');
    }
}
