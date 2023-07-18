<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class As400FreightTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('as400_freight')->delete();

        DB::table('as400_freight')->insert([

            [
                'brand_id' => 1,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 1,
            ],

            [
                'brand_id' => 2,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 2,
            ],

            [
                'brand_id' => 3,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 3,
            ],

            [
                'brand_id' => 5,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 4,
            ],

            [
                'brand_id' => 6,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 5,
            ],

            [
                'brand_id' => 8,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 6,
            ],

            [
                'brand_id' => 9,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 7,
            ],

            [
                'brand_id' => 7,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 8,
            ],

            [
                'brand_id' => 10,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 9,
            ],

            [
                'brand_id' => 4,
                'freight' => '0.00',
                'freight_included' => 0,
                'id' => 10,
            ],
        ]);
    }
}
