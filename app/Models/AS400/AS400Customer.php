<?php

namespace App\Models\AS400;

use App\Models\CustomerGLAccount;
use App\Traits\Orderable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class AS400Customer extends Model
{
    use HasFactory;
    use Orderable;

    const DEFAULT_GL = 92;

    const ORDER_BY = ['name' => 'asc', 'price_code' => 'asc'];

    public $timestamps = false;

    protected $table = 'as400_customers';

    public static function migrate()
    {
        $rows = DB::connection('as400')
            ->table('arcust')
            ->where('arc_company_no', 'P')
            ->whereIn('arc_status', ['A', 'H'])
            ->whereRaw("trim(arc_name) <> ''")
            ->whereRaw("left(trim(arc_name), 1) <> '*'")
            ->whereRaw("trim(arc_cust_no) <> ''")
            ->select(['arc_cust_no', 'arc_name', 'arc_addr_3', 'arc_alter_desc_code'])
            ->get();

        $migrationMappings = [
            'customer_number' => 'arc_cust_no',
            'name' => 'arc_name',
            'province' => 'arc_addr_3',
            'price_code' => 'arc_alter_desc_code',
        ];

        $records = [];
        foreach ($rows as $row) {
            $record = [];
            foreach ($migrationMappings as $local => $remote) {
                $record[$local] = utf8_encode(trim($row->$remote));
            }
            $records[] = $record;
        }

        AS400Customer::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400Customer::insert($chunk);
        }
    }

    public function getGlAccountAttribute()
    {
        return $this->customerGLAccount ? $this->customerGLAccount->gl_account : static::DEFAULT_GL;
    }

    public function customerGLAccount(): HasOne
    {
        return $this->hasOne(CustomerGLAccount::class, 'customer_number', 'customer_number');
    }
}
