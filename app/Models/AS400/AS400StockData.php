<?php

namespace App\Models\AS400;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400StockData extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'as400_stock_data';

    public static function migrate($products = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        $rows = DB::connection('as400')
            ->table('stmast')
            ->leftJoin('poline', function ($join) {
                $join->on('stm_stock_no', 'pol_stock_no')
                    ->where([
                        'pol_date_received' => '0001-01-01',
                        'pol_company_no' => 'P',
                    ]);
            })
            ->where('stm_company_no', 'P')
            ->select(['stm_stock_no', 'stm_desc', 'stm_desc_2', 'stm_stock_cat', 'stm_status_code', 'stm_misc_code_3', 'stm_stock_cat', 'stm_last_date_received', DB::raw('min(pol_expected_date) as pol_expected_date')])
            ->groupBy(['stm_stock_no', 'stm_desc', 'stm_desc_2', 'stm_stock_cat', 'stm_status_code', 'stm_misc_code_3', 'stm_stock_cat', 'stm_last_date_received'])
            ->get();

        $migrationMappings = [
            'status' => 'stm_status_code',
            'last_received' => 'stm_last_date_received',
            'category_code' => 'stm_stock_cat',
        ];

        $records = [];
        foreach ($rows as $row) {
            $stockId = preg_replace("/[^A-Za-z0-9\.]/", '', $row->stm_stock_no);
            if (! array_key_exists(trim($stockId), $products)) {
                continue;
            }

            $record = [];
            foreach ($migrationMappings as $local => $remote) {
                $record[$local] = utf8_encode(trim($row->$remote));
            }
            $record['product_id'] = $products[$stockId];
            $record['description'] = mb_convert_encoding(trim($row->stm_desc) . trim($row->stm_desc_2), 'UTF-8', 'UTF-8');
            $record['hide_from_catalogue'] = trim($row->stm_misc_code_3) == 'NO';
            $record['out_of_stock'] = trim($row->stm_misc_code_3) == 'OUT';
            $record['expected'] = empty(trim($row->pol_expected_date)) ? null : $row->pol_expected_date;
            $records[] = $record;
        }

        AS400StockData::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400StockData::insert($chunk);
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class)->withPending();
    }
}
