<?php

namespace App\Models\AS400;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class AS400WarehouseStock extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'as400_warehouse_stock';

    public static function migrate($products = null)
    {
        if (! $products) {
            $products = Product::select('id', 'stock_id')->get();
        }
        $products = array_column($products->toArray(), 'id', 'stock_id');

        $rows = DB::connection('as400')
            ->table('rbinv')
            ->join('stwhse', function ($join) {
                $join->on('rbt_whse_no', 'stw_whse_no')
                    ->on('rbt_stock_no', 'stw_stock_no')
                    ->where('stw_company_no', 'P');
            })
            ->where('rbt_company_no', 'P')
            ->select(['rbt_stock_no', 'rbt_whse_no', 'rbt_date_expires', DB::raw('sum(rbt_qty_oh) as rbt_qty_oh'), 'rbt_tag_no', 'stw_unit_cost', 'stw_misc_value'])
            ->groupBy(['rbt_stock_no', 'rbt_whse_no', 'rbt_date_expires', 'rbt_tag_no', 'stw_unit_cost', 'stw_misc_value'])
            ->get();

        $migrationMappings = [
            'warehouse' => 'rbt_whse_no',
            'expiry' => 'rbt_date_expires',
            'quantity' => 'rbt_qty_oh',
            'tag' => 'rbt_tag_no',
            'unit_cost' => 'stw_unit_cost',
            'average_landed_cost' => 'stw_misc_value',
        ];

        $records = [];
        foreach ($rows as $row) {
            $stockId = preg_replace("/[^A-Za-z0-9\.]/", '', $row->rbt_stock_no);
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

        AS400WarehouseStock::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400WarehouseStock::insert($chunk);
        }
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
