<?php

namespace App\Models\AS400;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400Freight extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'as400_freight';

    public static function migrate($brands = null)
    {
        if (! $brands) {
            $brands = Brand::select('id', 'brand_number')->get();
        }
        $brands = array_column($brands->toArray(), 'brand_number', 'id');

        $rows = DB::connection('as400')
            ->table('apvend')
            ->where('apv_company_no', 'P')
            ->where(DB::raw('trim(apv_vendor_no)'), '<>', '')
            ->whereIn('apv_status', ['A', 'H'])
            ->select(['apv_vendor_no', 'apv_freight_rate', 'apv_freight_included'])
            ->get();

        $records = [];
        foreach ($rows as $row) {
            $matchedBrands = array_filter($brands, function ($brandNumber) use ($row) {
                return trim($row->apv_vendor_no) == $brandNumber;
            });
            if (! $matchedBrands) {
                continue;
            }

            foreach (array_keys($matchedBrands) as $brandId) {
                $record = [];
                $record['freight_included'] = $row->apv_freight_included == 'Y';
                $record['freight'] = $row->apv_freight_rate ?? 0;
                $record['brand_id'] = $brandId;

                $records[] = $record;
            }
        }

        AS400Freight::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400Freight::insert($chunk);
        }
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
