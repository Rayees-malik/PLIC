<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class As400UpcomingPriceChangesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('as400_upcoming_price_changes')->delete();

        DB::table('as400_upcoming_price_changes')->insert([

            [
                'change_date' => '2021-01-01',
                'id' => 1,
                'product_id' => 73,
                'wholesale_price' => '29.60',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 2,
                'product_id' => 60,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 3,
                'product_id' => 59,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 4,
                'product_id' => 58,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 5,
                'product_id' => 57,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 6,
                'product_id' => 56,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 7,
                'product_id' => 55,
                'wholesale_price' => '15.78',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 8,
                'product_id' => 144,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 9,
                'product_id' => 143,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 10,
                'product_id' => 145,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 11,
                'product_id' => 148,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 12,
                'product_id' => 147,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 13,
                'product_id' => 139,
                'wholesale_price' => '8.28',
            ],

            [
                'change_date' => '2021-01-01',
                'id' => 14,
                'product_id' => 149,
                'wholesale_price' => '8.28',
            ],
        ]);
    }
}
