<?php

namespace App\Models\AS400;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AS400ZeusRetailer extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'as400_zeus_retailers';

    public static function migrate()
    {
        $rows = DB::connection('as400')
            ->table('inhdmy')
            ->join('inlnyr', function ($join) {
                $join->on('inh_invoice_no', 'inl_invoice_no')
                    ->where('inl_company_no', 'P');
            })
            ->join('arcust', function ($join) {
                $join->on('inh_cust_no', 'arc_cust_no')
                    ->where('arc_company_no', 'P');
            })
            ->where('inh_company_no', 'P')
            ->whereRaw('inh_inv_date > current date - 1 year')
            ->whereRaw('inl_inv_date > current date - 1 year')
            ->whereIn('inl_stock_cat', ['MODU', 'KYOL'])
            ->whereIn('arc_status', ['A', 'H'])
            ->whereIn('arc_industry_code', ['CDRUG', 'DRUG', 'GROC', 'HFS', 'OTHER'])
            ->whereNotIn('arc_conversion_custno', ['LOBLAW', 'LONGOS', 'OVERWAITEA', 'SOBEYS AT', 'SOBEYSON', 'SOBEYS-W', 'REXALL', 'SHOPPERS', 'FEDCOOP', 'LONDON'])
            ->whereRaw("length(trim(ifnull(nullif(inh_ship_to_postal, ''), inh_sold_to_postal))) = 7")
            ->whereRaw("ifnull(nullif(inh_ship_to, ''), inh_sold_to) not like '**'")
            ->whereNotIn(DB::raw("ifnull(nullif(inh_ship_to, ''), inh_sold_to)"), ['KATHERINE BELEJ', 'MIRIAM MCCREA', 'WENDY ROSS RMT'])
            ->select([
                'inh_cust_no',
                'inl_stock_cat',
                'inh_inv_date',
                DB::raw("ifnull(nullif(inh_ship_to, ''), inh_sold_to) as cust_name"),
                DB::raw("ifnull(nullif(inh_ship_to_2, ''), inh_sold_to_2) as cust_address"),
                DB::raw("ifnull(nullif(inh_ship_to_3, ''), inh_sold_to_3) as cust_city"),
                DB::raw("ifnull(nullif(inh_ship_to_4, ''), inh_sold_to_4) as cust_province"),
                DB::raw("ifnull(nullif(inh_ship_to_postal, ''), inh_sold_to_postal) as cust_postal"),
                'arc_contact_email',
                'arc_phone_no',
            ])
            ->get();

        $migrationMappings = [
            'customer_number' => 'inh_cust_no',
            'category' => 'inl_stock_cat',
            'invoice_date' => 'inh_inv_date',
            'name' => 'cust_name',
            'address' => 'cust_address',
            'city' => 'cust_city',
            'province' => 'cust_province',
            'postal_code' => 'cust_postal',
            'contact_email' => 'arc_contact_email',
            'contact_phone' => 'arc_phone_no',
        ];

        $records = [];
        foreach ($rows as $row) {
            $record = [];
            foreach ($migrationMappings as $local => $remote) {
                $record[$local] = utf8_encode(trim($row->$remote));
            }
            $records[] = $record;
        }

        AS400ZeusRetailer::truncate();
        foreach (array_chunk($records, 1000) as $chunk) {
            AS400ZeusRetailer::insert($chunk);
        }
    }
}
