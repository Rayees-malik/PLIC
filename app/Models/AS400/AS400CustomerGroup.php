<?php

namespace App\Models\AS400;

use Illuminate\Database\Eloquent\Model;

class AS400CustomerGroup extends Model
{
    public $timestamps = false;

    protected $table = 'as400_customer_groups';

    public static function migrate()
    {
        $rows = AS400Customer::where('price_code', '<>', '')
            ->groupBy('price_code')
            ->havingRaw('count(*) > 1')
            ->select('price_code')
            ->get();

        $records = [];
        foreach ($rows as $row) {
            $record = [];
            $record['code'] = $row['price_code'];
            $records[] = $record;
        }

        AS400CustomerGroup::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400CustomerGroup::insert($chunk);
        }
    }
}
