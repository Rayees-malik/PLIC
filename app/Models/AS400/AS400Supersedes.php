<?php

namespace App\Models\AS400;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400Supersedes extends Model
{
    public $timestamps = false;

    protected $table = 'as400_supersedes';

    public static function migrate($products = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        $rows = DB::connection('as400')
            ->table('stxref')
            ->leftJoin('stxref as stxjoin', function ($join) {
                $join->on('stxref.stx_stock_no', 'stxjoin.stx_stock_no')
                    ->on('stxref.stx_sequence', '<', 'stxjoin.stx_sequence')
                    ->where([
                        'stxjoin.stx_company_no' => 'P',
                        'stxjoin.stx_type' => 'S',
                    ]);
            })
            ->where([
                'stxref.stx_company_no' => 'P',
                'stxref.stx_type' => 'S',
            ])
            ->whereNull('stxjoin.stx_stock_no')
            ->select(['stxref.stx_stock_no', 'stxref.stx_stock_no_2'])
            ->get();

        $records = [];
        foreach ($rows as $row) {
            $stockId1 = preg_replace("/[^A-Za-z0-9\.]/", '', $row->stx_stock_no);
            $stockId2 = preg_replace("/[^A-Za-z0-9\.]/", '', $row->stx_stock_no_2);

            if (! array_key_exists($stockId1, $products)) {
                continue;
            }
            if (! array_key_exists($stockId2, $products)) {
                continue;
            }

            $record = [];
            $record['superseded_id'] = $products[$stockId1];
            $record['superseding_id'] = $products[$stockId2];
            $records[] = $record;
        }

        AS400Supersedes::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400Supersedes::insert($chunk);
        }
    }

    public function superseded(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class, 'superseded_id');
    }

    public function superseding(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Product::class, 'superseding_id');
    }
}
