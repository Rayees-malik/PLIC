<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandBrokerTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('brand_broker')->delete();

        DB::table('brand_broker')->insert([

            [
                'brand_id' => 1,
                'broker_id' => 19,
            ],

            [
                'brand_id' => 2,
                'broker_id' => 10,
            ],

            [
                'brand_id' => 3,
                'broker_id' => 19,
            ],

            [
                'brand_id' => 4,
                'broker_id' => 19,
            ],

            [
                'brand_id' => 5,
                'broker_id' => 19,
            ],

            [
                'brand_id' => 6,
                'broker_id' => 13,
            ],

            [
                'brand_id' => 7,
                'broker_id' => 12,
            ],

            [
                'brand_id' => 9,
                'broker_id' => 19,
            ],
        ]);
    }
}
