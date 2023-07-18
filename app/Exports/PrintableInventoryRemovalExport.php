<?php

namespace App\Exports;

use App\Models\InventoryRemoval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class PrintableInventoryRemovalExport extends BaseExport
{
    public function export($id)
    {
        $removal = InventoryRemoval::withAccess()->allStates()->with([
            'user' => function ($query) {
                $query->select('id', 'name');
            },
            'lineItems' => function ($query) {
                $query->with([
                    'product' => function ($query) {
                        $query->with(['brand' => function ($query) {
                            $query->with('as400Consignment')->select('id', 'name');
                        },
                        ])->select('id', 'name', 'name_fr', 'packaging_language', 'stock_id', 'brand_id');
                    },
                ]);
            },
            'signoff.responses.user',
            'signoff.responses',
        ])->findOrFail($id);

        $firstLineItem = $removal->lineItems->first();

        if (! $firstLineItem) {
            return redirect()->route('inventoryremovals.index');
        }

        $spreadsheet = $this->loadFile('templates/printable_inventory_removal.xlsx');

        $lineItemsSheet = $spreadsheet->getSheetByName('Line Items');

        $lineItemsSheet->setCellValue('B1', $removal->user->name);
        $lineItemsSheet->setCellValue('B2', $firstLineItem->warehouse);
        $lineItemsSheet->setCellValue('D2', $firstLineItem->product->brand->name);

        $reasons = Config::get('inventory-removals')['reasons'];

        $data = [];
        foreach ($removal->lineItems as $lineItem) {
            $options = implode(', ', array_filter([
                $lineItem->full_mcb ? 'Full MCB' : null,
                $lineItem->reserve ? 'Reserve' : null,
                $lineItem->vendor_pickup ? 'Vendor Pickup' : null,
            ]));

            $data[] = [
                $lineItem->product->stock_id,
                $lineItem->product->getName(),
                $lineItem->quantity,
                $lineItem->average_landed_cost,
                number_format($lineItem->quantity * $lineItem->average_landed_cost, 2),
                $lineItem->cost,
                number_format($lineItem->quantity * $lineItem->cost, 2),
                $lineItem->expiry,
                $options,
                strtolower(substr($lineItem->product->stock_id, -1)) == 'c' || optional($lineItem->product->brand->as400Consignment)->consignment ? 'Y' : 'N',
                Arr::get($reasons, $lineItem->reason, 'Other, See Notes'),
                $lineItem->notes,
            ];
        }

        $lineItemsSheet->fromArray($data, null, 'A4');

        $responsesSheet = $spreadsheet->getSheetByName('Signoff Responses');

        $signoff = $removal->signoffs->count() ? $removal->signoffs->first() : $removal->signoff;
        $signoffConfigSteps = $signoff->signoffConfigSteps()->pluck('name', 'step')->all();

        $responsesData = $signoff->responses->map(function ($response) use ($signoffConfigSteps) {
            return [
                $response->user->name,
                $response->comment_only && $response->comment == 'Submitted' ? 'Submitted' : $signoffConfigSteps[$response->step],
                $response->updated_at,
                str_replace(["\n", "\r"], ' ', $response->comment),
            ];
        })->toArray();

        $responsesSheet->fromArray($responsesData, null, 'A2');

        return $this->downloadFile($spreadsheet, 'inventory-removal.xlsx');
    }
}
