<?php

namespace App\Exports;

use App\Models\Signoff;
use Illuminate\Http\Request;

class BulkWebseriesExport extends CSVExport
{
    public function export(Request $request)
    {
        $exportDate = now()->format('Y-m-d_H-i-s');
        $filename = "bulk_webseries-{$exportDate}.csv";

        $signoffs = Signoff::pending()
            ->forUser()
            ->with(['initial', 'proposed'])
            ->whereIn('id', array_keys($request->selected))
            ->get();

        $data[] = [
            'STP_COMPANY_NO',
            'STP_DESC_NO',
            'STP_EXTRA',
            'STP_STOCK_NO',
            'STP_CHANGE_DATE',
            'STP_PRICE_1',
            'STP_PRICE_6',
        ];

        foreach ($signoffs as $signoff) {
            if ($signoff->proposed->unit_cost === $signoff->initial->unit_cost || is_null($signoff->proposed->price_change_date)) {
                continue;
            }
            // $price = $signoff->proposed->getPrice(null, $this->priceCode);
            // $minimumSellBy = $signoff->proposed->minimumSellBy;

            // $srp = round(($price * 1.8181) / $minimumSellBy, 2);

            $data[] = [
                'P', // always hard-coded
                null, // always hard-coded
                null, // always hard-coded
                $signoff->proposed->stock_id,
                $signoff->proposed->price_change_date?->toDateString(),
                $signoff->proposed->wholesale_price,
                $signoff->proposed->suggested_retail_price,
            ];
        }

        return $this->downloadFile($data, $filename);
    }
}
