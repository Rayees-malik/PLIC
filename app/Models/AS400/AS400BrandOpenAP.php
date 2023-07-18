<?php

namespace App\Models\AS400;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400BrandOpenAP extends Model
{
    public $timestamps = false;

    protected $table = 'as400_brand_open_ap';

    public static function migrate($brands = null)
    {
        if (! $brands) {
            $brands = Brand::select('id', 'brand_number', 'finance_brand_number')->get();
        }

        $brandNumbers = [];
        foreach ($brands as $brand) {
            $brandNumbers[$brand->id] = $brand->finance_brand_number ?? $brand->brand_number;
        }

        $rows = DB::connection('as400')
            ->table('apaccp')
            ->join('apalf', function ($join) {
                $join->on('app_vendor_no', 'apv_vendor_no')
                    ->where([
                        ['apv_company_no', '=', 'P'],
                        ['apv_balance', '<>', 0],
                    ])
                    ->whereIn('apv_status', ['A', 'H']);
            })
            ->where([
                ['app_company_no', '=', 'P'],
                ['app_balance', '<>', 0],
            ])
            ->whereBetween('app_vendor_no', ['0001', '9999'])
            ->whereYear('app_invoice_date', '>=', Carbon::now()->subYear(7)->year)
            ->select(['right(trim(app_vendor_no), 3) as app_vendor_no', 'app_invoice_no', 'app_invoice_date', 'app_reference', 'app_invoice_amount'])
            ->get();

        $migrationMappings = [
            'invoice_number' => 'app_invoice_no',
            'invoice_date' => 'app_invoice_date',
            'reference' => 'app_reference',
            'invoice_amount' => 'app_invoice_amount',
        ];

        $records = [];
        foreach ($rows as $row) {
            $matchedBrands = array_filter($brandNumbers, function ($brandNumber) use ($row) {
                return substr(preg_replace('/\D/', '', $brandNumber), -3) == $row->app_vendor_no;
            });
            if (! $matchedBrands) {
                continue;
            }

            foreach ($matchedBrands as $brandId => $brandNumber) {
                $record = [];
                foreach ($migrationMappings as $local => $remote) {
                    $record[$local] = utf8_encode(trim($row->$remote));
                }
                $record['brand_id'] = $brandId;

                $records[] = $record;
            }
        }

        AS400BrandOpenAP::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400BrandOpenAP::insert($chunk);
        }
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
