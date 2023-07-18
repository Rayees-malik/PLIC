<?php

namespace App\Models\AS400;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400Pricing extends Model
{
    use HasFactory;

    const EXTRA_ADDON_CODES = [
        1 => 1.9,
        2 => 2,
        3 => 2.3,
        4 => 1.5,
        5 => 0.7,
        6 => 2,
        7 => 1.4,
        8 => 6.8,
    ];

    public $timestamps = false;

    protected $table = 'as400_pricing';

    protected $casts = ['po_price_expiry' => 'date'];

    public static function migrate($products = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        $rows = DB::connection('as400')
            ->table('stmast')
            ->leftJoin('postvn', function ($join) {
                $join->on('stm_stock_no', 'pos_stock_no')
                    ->on('stm_prime_vendor', 'pos_vendor_no')
                    ->where('pos_company_no', 'P');
            })
            ->leftJoin('poduty', function ($join) {
                $join->on('pos_duty_code', 'pod_duty_code')
                    ->where('pod_type', 'D')
                    ->where('pod_company_no', 'P');
            })
            ->where('stm_company_no', 'P')
            ->select([
                'stm_stock_no', 'stm_price_1', 'stm_unit_cost', 'stm_tax_code', 'pos_current_price', 'pos_next_price',
                'pos_convert_units', 'pod_duty_percent', 'pos_date_price_expires', 'postvn.disc01', 'pos_extra_code',
            ])
            ->get();

        $migrationMappings = [
            'wholesale_price' => 'stm_price_1',
            'average_landed_cost' => 'stm_unit_cost',
        ];

        $records = [];
        foreach ($rows as $row) {
            $stockId = preg_replace("/[^A-Za-z0-9\.]/", '', $row->stm_stock_no);
            if (! array_key_exists($stockId, $products)) {
                continue;
            }

            $record = [];
            foreach ($migrationMappings as $local => $remote) {
                $record[$local] = utf8_encode(trim($row->$remote));
            }
            $record['product_id'] = $products[$stockId];
            $record['taxable'] = trim($row->stm_tax_code) == '1.1';
            $record['duty'] = empty(trim($row->pod_duty_percent)) ? 0 : $row->pod_duty_percent;
            $record['edlp_discount'] = empty(trim($row->disc01)) ? 0 : $row->disc01;

            if (is_nan($record['wholesale_price']) || $record['wholesale_price'] < 0) {
                $record['wholesale_price'] = 0;
            }

            $convertUnits = is_nan($row->pos_convert_units) || $row->pos_convert_units < 1 ? 1 : $row->pos_convert_units;
            $nextPrice = round(($row->pos_next_price ?? 0) / $convertUnits, 2);

            if ($row->pos_extra_code && array_key_exists($row->pos_extra_code, AS400Pricing::EXTRA_ADDON_CODES)) {
                $record['extra_addon_percent'] = AS400Pricing::EXTRA_ADDON_CODES[$row->pos_extra_code];
            } else {
                $record['extra_addon_percent'] = null;
            }

            $record['po_price'] = round(($row->pos_current_price ?? 0) / $convertUnits, 2);
            $record['next_po_price'] = $nextPrice > 0 ? $nextPrice : null;
            $record['po_price_expiry'] = $nextPrice > 0 ? $row->pos_date_price_expires : null;
            $records[] = $record;
        }

        AS400Pricing::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400Pricing::insert($chunk);
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
