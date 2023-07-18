<?php

namespace App\Exports;

use App\Helpers\ZipHelper;
use App\Models\Brand;
use App\Models\PromoPeriod;
use Illuminate\Http\Request;

class BulkOrderFormExport
{
    public function export(Request $request)
    {
        $brandNumbers = explode(' ', preg_replace('/\ +/', ' ', preg_replace('/[^A-Za-z0-9\ ]/', ' ', $request->brands)));

        $periodName = '';
        $periodId1 = $request->get('period_id1');
        $period1 = $periodId1 ? PromoPeriod::select('name')->find($periodId1) : null;
        $periodName = $period1 ? " {$period1->name} " : '';

        $brands = Brand::select('id', 'name');
        foreach ($brandNumbers as $key => $number) {
            if ($key == 0) {
                $brands->where('brand_number', 'like', "%{$number}");
            } else {
                $brands->orWhere('brand_number', 'like', "%{$number}");
            }
        }
        $brands = $brands->get();

        if (! $brands->count()) {
            flash('No valid brand numbers selected.', 'danger');

            return redirect()->route('exports.index');
        }

        $orderFormExport = new OrderFormExport;
        $files = [];
        foreach ($brands as $brand) {
            $request->merge(['brand_id' => $brand->id]);
            $filename = $orderFormExport->export($request, downloadFile: false, asBulkExport: true);
            if (! $filename) {
                continue;
            }
            $brandName = str_replace('/', '_', $brand->name);
            $files["{$brandName} -{$periodName}Order Form.xlsx"] = $filename;
        }

        if (empty($files)) {
            flash('No order forms were able to be generated.', 'danger');

            return redirect()->route('exports.index');
        }

        $zipFile = ZipHelper::zipFiles($files, true);

        return ZipHelper::download($zipFile, 'BulkOrderForms.zip');
    }
}
