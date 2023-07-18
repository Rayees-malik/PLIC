<?php

namespace App\Models\AS400;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400SpecialPricing extends Model
{
    public $timestamps = false;

    protected $table = 'as400_special_pricing';

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public static function migrate($products = null, $brands = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        if (! $brands) {
            $brands = Brand::select('id', 'category_code')->get();
        }
        $brands = array_column($brands->toArray(), 'category_code', 'id');

        $rows = DB::connection('as400')
            ->table('stalt')
            ->select(['std_stock_no', 'std_price', 'std_desc_no', 'std_date_effective', 'std_date_expiry', 'std_discount', 'std_extra'])
            ->where('std_company_no', 'P')
            ->get();

        $migrationMappings = [
            'price' => 'std_price',
            'price_code' => 'std_desc_no',
            'start_date' => 'std_date_effective',
            'end_date' => 'std_date_expiry',
            'extra' => 'std_extra',
        ];

        $records = [];
        foreach ($rows as $row) {
            $stockId = preg_replace("/[^A-Za-z0-9\.]/", '', $row->std_stock_no);
            if (! array_key_exists($stockId, $products)) {
                if (strlen(preg_replace('/[0-9]+/', '', $stockId)) < 2) {
                    continue;
                }

                $stockId = strtoupper($stockId);
                $matchedBrands = array_filter($brands, function ($categoryCode) use ($stockId) {
                    return strtoupper($categoryCode) == $stockId;
                });
                foreach (array_keys($matchedBrands) as $brandId) {
                    $record = [];
                    foreach ($migrationMappings as $local => $remote) {
                        $record[$local] = utf8_encode(trim($row->$remote));
                    }
                    $record['priceable_id'] = $brandId;
                    $record['priceable_type'] = \App\Models\Brand::class;
                    $record['percent_discount'] = (float) $row->std_discount / 100;

                    $records[] = $record;
                }
            } else {
                $record = [];
                foreach ($migrationMappings as $local => $remote) {
                    $record[$local] = utf8_encode(trim($row->$remote));
                }
                $record['priceable_id'] = $products[$stockId];
                $record['priceable_type'] = \App\Models\Product::class;
                $record['percent_discount'] = (float) $row->std_discount / 100;
                $records[] = $record;
            }
        }

        AS400SpecialPricing::truncate();
        foreach (array_chunk($records, 2500) as $chunk) {
            AS400SpecialPricing::insert($chunk);
        }
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('price_code', $code);
    }

    public function scopeForDate($query, $date = null)
    {
        if (! $date) {
            $date = date('Y/m/d');
        }

        return $query->whereDate('start_date', '<=', $date)->whereDate('end_date', '>=', $date);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
