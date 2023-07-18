<?php

namespace App\Models\AS400;

use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400BrandPOReceived extends Model
{
    public $timestamps = false;

    protected $table = 'as400_brand_po_received';

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
            ->table('polnbl')
            ->whereBetween('pol_vendor_no', ['0001', '9999'])
            ->whereYear('pol_date_received', '>=', Carbon::now()->subYear(7)->year)
            ->where([
                ['pol_company_no', '=', 'P'],
                ['pol_value_fair_market', '>', 0],
                ['pol_status', '=', 'R'],
            ])
            ->select(['right(trim(pol_vendor_no), 3) as pol_vendor_no', 'pol_bol_no', 'pol_date_received', 'pol_status'])
            ->groupBy(['pol_vendor_no', 'pol_bol_no', 'pol_date_received', 'pol_status'])
            ->get();

        $migrationMappings = [
            'po_number' => 'pol_bol_no',
            'po_date' => 'pol_date_received',
            'status' => 'pol_status',
        ];

        $records = [];
        foreach ($rows as $row) {
            $matchedBrands = array_filter($brandNumbers, function ($brandNumber) use ($row) {
                return substr(preg_replace('/\D/', '', $brandNumber), -3) == $row->pol_vendor_no;
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

        AS400BrandPOReceived::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400BrandPOReceived::insert($chunk);
        }
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
