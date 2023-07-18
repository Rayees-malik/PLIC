<?php

namespace App\Models\AS400;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400UpcomingPriceChange extends Model
{
    public $timestamps = false;

    protected $table = 'as400_upcoming_price_changes';

    protected $casts = ['change_date' => 'date'];

    public static function migrate($products = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        $rows = DB::connection('as400')
            ->table('stprc')
            ->where('stp_company_no', 'P')
            ->select(['stp_stock_no', 'stp_change_date', 'stp_price_1'])
            ->get();

        $migrationMappings = [
            'change_date' => 'stp_change_date',
            'wholesale_price' => 'stp_price_1',
        ];

        $records = [];
        foreach ($rows as $row) {
            $stockId = preg_replace("/[^A-Za-z0-9\.]/", '', $row->stp_stock_no);
            if (! array_key_exists($stockId, $products)) {
                continue;
            }

            $record = [];
            foreach ($migrationMappings as $local => $remote) {
                $record[$local] = utf8_encode(trim($row->$remote));
            }
            $record['product_id'] = $products[$stockId];
            $records[] = $record;
        }

        AS400UpcomingPriceChange::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400UpcomingPriceChange::insert($chunk);
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
