<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class As400WarehouseStockTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('as400_warehouse_stock')->delete();

        DB::table('as400_warehouse_stock')->insert([

            [
                'expiry' => '2023-10-09',
                'id' => 1,
                'product_id' => 51,
                'quantity' => 49,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 2,
                'product_id' => 62,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '52.72',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 3,
                'product_id' => 88,
                'quantity' => 13,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 4,
                'product_id' => 126,
                'quantity' => 53,
                'tag' => '',
                'unit_cost' => '8.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 5,
                'product_id' => 136,
                'quantity' => 52,
                'tag' => '',
                'unit_cost' => '36.95',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 6,
                'product_id' => 134,
                'quantity' => 41,
                'tag' => '',
                'unit_cost' => '37.88',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 7,
                'product_id' => 81,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-31',
                'id' => 8,
                'product_id' => 80,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 9,
                'product_id' => 35,
                'quantity' => 31,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-04-21',
                'id' => 10,
                'product_id' => 134,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '36.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 11,
                'product_id' => 196,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 12,
                'product_id' => 146,
                'quantity' => 147,
                'tag' => '',
                'unit_cost' => '5.86',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2021-10-01',
                'id' => 13,
                'product_id' => 72,
                'quantity' => 4,
                'tag' => '192961',
                'unit_cost' => '12.97',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 14,
                'product_id' => 63,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 15,
                'product_id' => 62,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 16,
                'product_id' => 180,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 17,
                'product_id' => 120,
                'quantity' => 54,
                'tag' => '',
                'unit_cost' => '5.53',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 18,
                'product_id' => 188,
                'quantity' => 66,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 19,
                'product_id' => 187,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-09-14',
                'id' => 20,
                'product_id' => 125,
                'quantity' => 48,
                'tag' => '',
                'unit_cost' => '7.42',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-05-01',
                'id' => 21,
                'product_id' => 79,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-02',
                'id' => 22,
                'product_id' => 19,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '12.34',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 23,
                'product_id' => 137,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '44.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 24,
                'product_id' => 97,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-04',
                'id' => 25,
                'product_id' => 149,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 26,
                'product_id' => 97,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 27,
                'product_id' => 112,
                'quantity' => 135,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 28,
                'product_id' => 4,
                'quantity' => 60,
                'tag' => '0P282',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 29,
                'product_id' => 137,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '44.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-02-01',
                'id' => 30,
                'product_id' => 82,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '13.88',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 31,
                'product_id' => 57,
                'quantity' => 60,
                'tag' => '',
                'unit_cost' => '11.43',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 32,
                'product_id' => 103,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 33,
                'product_id' => 85,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 34,
                'product_id' => 6,
                'quantity' => 48,
                'tag' => '0P312',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-04-01',
                'id' => 35,
                'product_id' => 139,
                'quantity' => 59,
                'tag' => '',
                'unit_cost' => '5.89',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 36,
                'product_id' => 145,
                'quantity' => 197,
                'tag' => '',
                'unit_cost' => '6.13',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 37,
                'product_id' => 149,
                'quantity' => 50,
                'tag' => '',
                'unit_cost' => '5.88',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 38,
                'product_id' => 147,
                'quantity' => 83,
                'tag' => '',
                'unit_cost' => '5.89',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-09-03',
                'id' => 39,
                'product_id' => 161,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-15',
                'id' => 40,
                'product_id' => 65,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '6.17',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-09',
                'id' => 41,
                'product_id' => 44,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '1.24',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 42,
                'product_id' => 67,
                'quantity' => 114,
                'tag' => '',
                'unit_cost' => '6.22',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 43,
                'product_id' => 1,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '10.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 44,
                'product_id' => 70,
                'quantity' => 132,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 45,
                'product_id' => 58,
                'quantity' => 58,
                'tag' => '',
                'unit_cost' => '11.58',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 46,
                'product_id' => 63,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 47,
                'product_id' => 121,
                'quantity' => 46,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 48,
                'product_id' => 109,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 49,
                'product_id' => 61,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-04-22',
                'id' => 50,
                'product_id' => 25,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '12.70',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 51,
                'product_id' => 149,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 52,
                'product_id' => 130,
                'quantity' => 40,
                'tag' => '',
                'unit_cost' => '36.91',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 53,
                'product_id' => 51,
                'quantity' => 63,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 54,
                'product_id' => 5,
                'quantity' => 1,
                'tag' => '113170H122',
                'unit_cost' => '4.25',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-05-01',
                'id' => 55,
                'product_id' => 85,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-10-23',
                'id' => 56,
                'product_id' => 148,
                'quantity' => 96,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 57,
                'product_id' => 41,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '1.15',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 58,
                'product_id' => 185,
                'quantity' => 51,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 59,
                'product_id' => 66,
                'quantity' => 201,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 60,
                'product_id' => 128,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '12.30',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 61,
                'product_id' => 93,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-02-01',
                'id' => 62,
                'product_id' => 190,
                'quantity' => 64,
                'tag' => '9B211',
                'unit_cost' => '14.43',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-05-20',
                'id' => 63,
                'product_id' => 122,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '5.81',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 64,
                'product_id' => 133,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '38.75',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-31',
                'id' => 65,
                'product_id' => 47,
                'quantity' => 42,
                'tag' => '',
                'unit_cost' => '1.73',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 66,
                'product_id' => 109,
                'quantity' => 114,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 67,
                'product_id' => 17,
                'quantity' => 179,
                'tag' => '110005',
                'unit_cost' => '3.05',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 68,
                'product_id' => 42,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 69,
                'product_id' => 130,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '37.32',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-01',
                'id' => 70,
                'product_id' => 189,
                'quantity' => 53,
                'tag' => '160239L171',
                'unit_cost' => '9.62',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-03-01',
                'id' => 71,
                'product_id' => 59,
                'quantity' => 38,
                'tag' => '',
                'unit_cost' => '11.43',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 72,
                'product_id' => 25,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '12.95',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 73,
                'product_id' => 187,
                'quantity' => 150,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 74,
                'product_id' => 121,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 75,
                'product_id' => 130,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '37.32',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 76,
                'product_id' => 179,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-05',
                'id' => 77,
                'product_id' => 147,
                'quantity' => 88,
                'tag' => '',
                'unit_cost' => '5.88',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2021-11-01',
                'id' => 78,
                'product_id' => 7,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '7.39',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 79,
                'product_id' => 54,
                'quantity' => 6,
                'tag' => '27383',
                'unit_cost' => '12.02',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-07',
                'id' => 80,
                'product_id' => 111,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 81,
                'product_id' => 104,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 82,
                'product_id' => 32,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 83,
                'product_id' => 87,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-14',
                'id' => 84,
                'product_id' => 111,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 85,
                'product_id' => 132,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '37.23',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 86,
                'product_id' => 56,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '11.56',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-11',
                'id' => 87,
                'product_id' => 22,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '12.95',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 88,
                'product_id' => 100,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 89,
                'product_id' => 158,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 90,
                'product_id' => 7,
                'quantity' => 38,
                'tag' => '',
                'unit_cost' => '7.39',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-06',
                'id' => 91,
                'product_id' => 130,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '37.32',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-09-03',
                'id' => 92,
                'product_id' => 197,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 93,
                'product_id' => 198,
                'quantity' => 29,
                'tag' => '',
                'unit_cost' => '3.33',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 94,
                'product_id' => 156,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 95,
                'product_id' => 197,
                'quantity' => 14,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 96,
                'product_id' => 196,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-13',
                'id' => 97,
                'product_id' => 133,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '37.12',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 98,
                'product_id' => 69,
                'quantity' => 200,
                'tag' => '',
                'unit_cost' => '6.32',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 99,
                'product_id' => 92,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 100,
                'product_id' => 15,
                'quantity' => 12,
                'tag' => '113930I101',
                'unit_cost' => '4.25',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 101,
                'product_id' => 148,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '5.92',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-06-19',
                'id' => 102,
                'product_id' => 121,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '5.82',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 103,
                'product_id' => 48,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-18',
                'id' => 104,
                'product_id' => 162,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 105,
                'product_id' => 26,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '9.91',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 106,
                'product_id' => 134,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '36.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-23',
                'id' => 107,
                'product_id' => 127,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '8.01',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 108,
                'product_id' => 133,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '36.98',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 109,
                'product_id' => 138,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '11.70',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 110,
                'product_id' => 26,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '9.91',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 111,
                'product_id' => 136,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '36.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 112,
                'product_id' => 183,
                'quantity' => 20,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 113,
                'product_id' => 112,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 114,
                'product_id' => 1,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '10.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-16',
                'id' => 115,
                'product_id' => 200,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '12.92',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-25',
                'id' => 116,
                'product_id' => 193,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 117,
                'product_id' => 180,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 118,
                'product_id' => 196,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 119,
                'product_id' => 8,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '2.64',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 120,
                'product_id' => 143,
                'quantity' => 72,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 121,
                'product_id' => 40,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-07',
                'id' => 122,
                'product_id' => 32,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 123,
                'product_id' => 71,
                'quantity' => 72,
                'tag' => '',
                'unit_cost' => '6.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 124,
                'product_id' => 62,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '52.72',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 125,
                'product_id' => 130,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '37.64',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 126,
                'product_id' => 54,
                'quantity' => 6,
                'tag' => '27383',
                'unit_cost' => '11.34',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 127,
                'product_id' => 80,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 128,
                'product_id' => 200,
                'quantity' => 72,
                'tag' => '',
                'unit_cost' => '12.97',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 129,
                'product_id' => 13,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-11',
                'id' => 130,
                'product_id' => 144,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 131,
                'product_id' => 163,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '22.19',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-25',
                'id' => 132,
                'product_id' => 112,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 133,
                'product_id' => 83,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 134,
                'product_id' => 63,
                'quantity' => 21,
                'tag' => '',
                'unit_cost' => '53.25',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 135,
                'product_id' => 104,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 136,
                'product_id' => 10,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 137,
                'product_id' => 123,
                'quantity' => 51,
                'tag' => '',
                'unit_cost' => '5.53',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 138,
                'product_id' => 39,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '0.75',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 139,
                'product_id' => 183,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 140,
                'product_id' => 47,
                'quantity' => 88,
                'tag' => '',
                'unit_cost' => '1.73',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 141,
                'product_id' => 9,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 142,
                'product_id' => 3,
                'quantity' => 21,
                'tag' => '',
                'unit_cost' => '8.23',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 143,
                'product_id' => 100,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 144,
                'product_id' => 143,
                'quantity' => 37,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 145,
                'product_id' => 114,
                'quantity' => 54,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 146,
                'product_id' => 41,
                'quantity' => 77,
                'tag' => '',
                'unit_cost' => '1.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-06-24',
                'id' => 147,
                'product_id' => 94,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-06',
                'id' => 148,
                'product_id' => 79,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-08-26',
                'id' => 149,
                'product_id' => 95,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 150,
                'product_id' => 200,
                'quantity' => 32,
                'tag' => '',
                'unit_cost' => '12.97',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 151,
                'product_id' => 137,
                'quantity' => 32,
                'tag' => '',
                'unit_cost' => '45.34',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 152,
                'product_id' => 39,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '0.75',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 153,
                'product_id' => 183,
                'quantity' => 13,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 154,
                'product_id' => 52,
                'quantity' => 99,
                'tag' => '',
                'unit_cost' => '0.88',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-18',
                'id' => 155,
                'product_id' => 135,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '45.78',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 156,
                'product_id' => 110,
                'quantity' => 13,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 157,
                'product_id' => 51,
                'quantity' => 415,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 158,
                'product_id' => 66,
                'quantity' => 341,
                'tag' => '',
                'unit_cost' => '6.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-02-01',
                'id' => 159,
                'product_id' => 53,
                'quantity' => 6,
                'tag' => '26986',
                'unit_cost' => '6.95',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 160,
                'product_id' => 33,
                'quantity' => 73,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 161,
                'product_id' => 145,
                'quantity' => 16,
                'tag' => '',
                'unit_cost' => '5.97',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-03-01',
                'id' => 162,
                'product_id' => 49,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-30',
                'id' => 163,
                'product_id' => 59,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.67',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 164,
                'product_id' => 37,
                'quantity' => 70,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 165,
                'product_id' => 130,
                'quantity' => 17,
                'tag' => '',
                'unit_cost' => '37.64',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 166,
                'product_id' => 62,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-02',
                'id' => 167,
                'product_id' => 200,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '12.92',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 168,
                'product_id' => 125,
                'quantity' => 39,
                'tag' => '',
                'unit_cost' => '7.58',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 169,
                'product_id' => 199,
                'quantity' => 8,
                'tag' => '110001',
                'unit_cost' => '7.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-10-15',
                'id' => 170,
                'product_id' => 127,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '8.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 171,
                'product_id' => 146,
                'quantity' => 276,
                'tag' => '',
                'unit_cost' => '5.86',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 172,
                'product_id' => 68,
                'quantity' => 193,
                'tag' => '',
                'unit_cost' => '6.18',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 173,
                'product_id' => 67,
                'quantity' => 224,
                'tag' => '',
                'unit_cost' => '6.33',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-07',
                'id' => 174,
                'product_id' => 180,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 175,
                'product_id' => 115,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2021-11-13',
                'id' => 176,
                'product_id' => 32,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 177,
                'product_id' => 136,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '37.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 178,
                'product_id' => 132,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '37.23',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 179,
                'product_id' => 65,
                'quantity' => 201,
                'tag' => '',
                'unit_cost' => '6.16',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-09-01',
                'id' => 180,
                'product_id' => 150,
                'quantity' => 18,
                'tag' => '1921650',
                'unit_cost' => '6.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 181,
                'product_id' => 117,
                'quantity' => 8,
                'tag' => '0281760',
                'unit_cost' => '18.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 182,
                'product_id' => 87,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 183,
                'product_id' => 30,
                'quantity' => 17919,
                'tag' => 'OE112',
                'unit_cost' => '4.25',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 184,
                'product_id' => 182,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-07-23',
                'id' => 185,
                'product_id' => 132,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '37.23',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 186,
                'product_id' => 47,
                'quantity' => 131,
                'tag' => '',
                'unit_cost' => '1.73',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 187,
                'product_id' => 83,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 188,
                'product_id' => 24,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '15.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 189,
                'product_id' => 99,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-04-01',
                'id' => 190,
                'product_id' => 199,
                'quantity' => 40,
                'tag' => '09-0207',
                'unit_cost' => '7.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2021-10-01',
                'id' => 191,
                'product_id' => 72,
                'quantity' => 34,
                'tag' => '192961MIA',
                'unit_cost' => '12.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 192,
                'product_id' => 43,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '1.28',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 193,
                'product_id' => 108,
                'quantity' => 148,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 194,
                'product_id' => 143,
                'quantity' => 90,
                'tag' => '',
                'unit_cost' => '6.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 195,
                'product_id' => 46,
                'quantity' => 17,
                'tag' => '',
                'unit_cost' => '1.55',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-01-01',
                'id' => 196,
                'product_id' => 67,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '6.22',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-08-01',
                'id' => 197,
                'product_id' => 8,
                'quantity' => 186,
                'tag' => '',
                'unit_cost' => '2.64',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-29',
                'id' => 198,
                'product_id' => 1,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '9.85',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 199,
                'product_id' => 101,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 200,
                'product_id' => 132,
                'quantity' => 37,
                'tag' => '',
                'unit_cost' => '37.58',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 201,
                'product_id' => 107,
                'quantity' => 34,
                'tag' => '06429093',
                'unit_cost' => '16.87',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 202,
                'product_id' => 138,
                'quantity' => 53,
                'tag' => '',
                'unit_cost' => '11.70',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 203,
                'product_id' => 155,
                'quantity' => 13,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 204,
                'product_id' => 96,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 205,
                'product_id' => 75,
                'quantity' => 4,
                'tag' => '0281760',
                'unit_cost' => '18.00',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-11-22',
                'id' => 206,
                'product_id' => 26,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '9.85',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 207,
                'product_id' => 133,
                'quantity' => 81,
                'tag' => '',
                'unit_cost' => '37.12',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 208,
                'product_id' => 82,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '15.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 209,
                'product_id' => 184,
                'quantity' => 64,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 210,
                'product_id' => 192,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 211,
                'product_id' => 183,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 212,
                'product_id' => 62,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-19',
                'id' => 213,
                'product_id' => 26,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '10.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 214,
                'product_id' => 114,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 215,
                'product_id' => 182,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 216,
                'product_id' => 131,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '37.89',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 217,
                'product_id' => 111,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-22',
                'id' => 218,
                'product_id' => 121,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '5.82',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-02',
                'id' => 219,
                'product_id' => 192,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-04-16',
                'id' => 220,
                'product_id' => 128,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '12.30',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 221,
                'product_id' => 62,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 222,
                'product_id' => 64,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 223,
                'product_id' => 40,
                'quantity' => 42,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 224,
                'product_id' => 137,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '44.27',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 225,
                'product_id' => 125,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '7.58',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 226,
                'product_id' => 23,
                'quantity' => 14,
                'tag' => '',
                'unit_cost' => '12.30',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-12-17',
                'id' => 227,
                'product_id' => 78,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 228,
                'product_id' => 98,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 229,
                'product_id' => 156,
                'quantity' => 40,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 230,
                'product_id' => 105,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 231,
                'product_id' => 122,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '5.55',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-07',
                'id' => 232,
                'product_id' => 184,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-03-01',
                'id' => 233,
                'product_id' => 162,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 234,
                'product_id' => 48,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 235,
                'product_id' => 128,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '11.70',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 236,
                'product_id' => 108,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 237,
                'product_id' => 156,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 238,
                'product_id' => 102,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-07',
                'id' => 239,
                'product_id' => 103,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 240,
                'product_id' => 9,
                'quantity' => 16,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-24',
                'id' => 241,
                'product_id' => 18,
                'quantity' => 19,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-07-19',
                'id' => 242,
                'product_id' => 161,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 243,
                'product_id' => 30,
                'quantity' => 17476,
                'tag' => '0E112',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 244,
                'product_id' => 14,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 245,
                'product_id' => 182,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 246,
                'product_id' => 115,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-03-01',
                'id' => 247,
                'product_id' => 57,
                'quantity' => 39,
                'tag' => '',
                'unit_cost' => '11.43',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 248,
                'product_id' => 29,
                'quantity' => 223,
                'tag' => '0042510',
                'unit_cost' => '7.50',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 249,
                'product_id' => 181,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 250,
                'product_id' => 54,
                'quantity' => 23,
                'tag' => '27383',
                'unit_cost' => '12.02',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 251,
                'product_id' => 140,
                'quantity' => 2,
                'tag' => '110001',
                'unit_cost' => '26.19',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-02',
                'id' => 252,
                'product_id' => 106,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 253,
                'product_id' => 32,
                'quantity' => 76,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2021-12-01',
                'id' => 254,
                'product_id' => 165,
                'quantity' => 292,
                'tag' => '',
                'unit_cost' => '6.90',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 255,
                'product_id' => 6,
                'quantity' => 12,
                'tag' => '0P312',
                'unit_cost' => '4.25',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 256,
                'product_id' => 180,
                'quantity' => 96,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 257,
                'product_id' => 102,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 258,
                'product_id' => 81,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 259,
                'product_id' => 114,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 260,
                'product_id' => 179,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 261,
                'product_id' => 44,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '1.24',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 262,
                'product_id' => 200,
                'quantity' => 26,
                'tag' => '',
                'unit_cost' => '12.97',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 263,
                'product_id' => 95,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 264,
                'product_id' => 113,
                'quantity' => 164,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 265,
                'product_id' => 180,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 266,
                'product_id' => 134,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '37.03',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 267,
                'product_id' => 98,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 268,
                'product_id' => 42,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 269,
                'product_id' => 50,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 270,
                'product_id' => 87,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 271,
                'product_id' => 158,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 272,
                'product_id' => 46,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '1.55',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-25',
                'id' => 273,
                'product_id' => 154,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-12-01',
                'id' => 274,
                'product_id' => 78,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 275,
                'product_id' => 33,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-01',
                'id' => 276,
                'product_id' => 190,
                'quantity' => 30,
                'tag' => 'OA291',
                'unit_cost' => '14.43',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 277,
                'product_id' => 112,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 278,
                'product_id' => 86,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 279,
                'product_id' => 131,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '37.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 280,
                'product_id' => 14,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 281,
                'product_id' => 38,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '0.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 282,
                'product_id' => 37,
                'quantity' => 329,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 283,
                'product_id' => 200,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '12.92',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-06',
                'id' => 284,
                'product_id' => 124,
                'quantity' => 79,
                'tag' => '',
                'unit_cost' => '7.39',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 285,
                'product_id' => 71,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '6.16',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-01',
                'id' => 286,
                'product_id' => 189,
                'quantity' => 12,
                'tag' => '160239L171',
                'unit_cost' => '9.62',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-18',
                'id' => 287,
                'product_id' => 1,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '10.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-22',
                'id' => 288,
                'product_id' => 28,
                'quantity' => 22,
                'tag' => '',
                'unit_cost' => '7.76',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 289,
                'product_id' => 137,
                'quantity' => 48,
                'tag' => '',
                'unit_cost' => '44.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 290,
                'product_id' => 23,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '11.75',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-09',
                'id' => 291,
                'product_id' => 41,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '1.15',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 292,
                'product_id' => 127,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '8.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 293,
                'product_id' => 98,
                'quantity' => 65,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 294,
                'product_id' => 30,
                'quantity' => 6,
                'tag' => 'OE112',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 295,
                'product_id' => 17,
                'quantity' => 175,
                'tag' => '110007',
                'unit_cost' => '3.05',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 296,
                'product_id' => 70,
                'quantity' => 177,
                'tag' => '',
                'unit_cost' => '6.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 297,
                'product_id' => 22,
                'quantity' => 17,
                'tag' => '',
                'unit_cost' => '12.95',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 298,
                'product_id' => 144,
                'quantity' => 45,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-08-01',
                'id' => 299,
                'product_id' => 107,
                'quantity' => 16,
                'tag' => '06429493',
                'unit_cost' => '16.87',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 300,
                'product_id' => 41,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '1.15',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 301,
                'product_id' => 185,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 302,
                'product_id' => 105,
                'quantity' => 62,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 303,
                'product_id' => 110,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 304,
                'product_id' => 143,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 305,
                'product_id' => 80,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 306,
                'product_id' => 28,
                'quantity' => 27,
                'tag' => '',
                'unit_cost' => '7.76',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 307,
                'product_id' => 52,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '0.88',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-25',
                'id' => 308,
                'product_id' => 69,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '6.17',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-02-01',
                'id' => 309,
                'product_id' => 53,
                'quantity' => 5,
                'tag' => '26986',
                'unit_cost' => '6.56',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-04',
                'id' => 310,
                'product_id' => 89,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 311,
                'product_id' => 50,
                'quantity' => 19,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 312,
                'product_id' => 62,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 313,
                'product_id' => 144,
                'quantity' => 78,
                'tag' => '',
                'unit_cost' => '6.06',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 314,
                'product_id' => 133,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '36.98',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 315,
                'product_id' => 85,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 316,
                'product_id' => 69,
                'quantity' => 169,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 317,
                'product_id' => 15,
                'quantity' => 6,
                'tag' => '113930I101',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 318,
                'product_id' => 184,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 319,
                'product_id' => 103,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-28',
                'id' => 320,
                'product_id' => 49,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-28',
                'id' => 321,
                'product_id' => 23,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '11.75',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 322,
                'product_id' => 135,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '46.52',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 323,
                'product_id' => 107,
                'quantity' => 19,
                'tag' => '06425893',
                'unit_cost' => '16.87',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 324,
                'product_id' => 94,
                'quantity' => 52,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 325,
                'product_id' => 40,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '1.02',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 326,
                'product_id' => 156,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-07',
                'id' => 327,
                'product_id' => 86,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 328,
                'product_id' => 111,
                'quantity' => 16,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 329,
                'product_id' => 7,
                'quantity' => 21,
                'tag' => '',
                'unit_cost' => '7.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 330,
                'product_id' => 101,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 331,
                'product_id' => 129,
                'quantity' => 105,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2021-10-01',
                'id' => 332,
                'product_id' => 72,
                'quantity' => 24,
                'tag' => '192961MIA',
                'unit_cost' => '13.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 333,
                'product_id' => 107,
                'quantity' => 33,
                'tag' => '06429093',
                'unit_cost' => '16.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 334,
                'product_id' => 69,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 335,
                'product_id' => 191,
                'quantity' => 61,
                'tag' => '',
                'unit_cost' => '9.62',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 336,
                'product_id' => 136,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '36.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 337,
                'product_id' => 92,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 338,
                'product_id' => 126,
                'quantity' => 16,
                'tag' => '',
                'unit_cost' => '8.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-05-01',
                'id' => 339,
                'product_id' => 141,
                'quantity' => 10,
                'tag' => '100285',
                'unit_cost' => '31.52',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-23',
                'id' => 340,
                'product_id' => 24,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '15.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 341,
                'product_id' => 65,
                'quantity' => 275,
                'tag' => '',
                'unit_cost' => '6.16',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 342,
                'product_id' => 58,
                'quantity' => 60,
                'tag' => '',
                'unit_cost' => '11.11',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 343,
                'product_id' => 39,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '0.75',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 344,
                'product_id' => 186,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 345,
                'product_id' => 27,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '5.74',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-06',
                'id' => 346,
                'product_id' => 131,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '37.89',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-26',
                'id' => 347,
                'product_id' => 146,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-01',
                'id' => 348,
                'product_id' => 189,
                'quantity' => 67,
                'tag' => '9L171',
                'unit_cost' => '9.62',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 349,
                'product_id' => 13,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-04-01',
                'id' => 350,
                'product_id' => 73,
                'quantity' => 12,
                'tag' => '0811910',
                'unit_cost' => '20.40',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-14',
                'id' => 351,
                'product_id' => 20,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 352,
                'product_id' => 108,
                'quantity' => 60,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 353,
                'product_id' => 165,
                'quantity' => 27,
                'tag' => '',
                'unit_cost' => '7.23',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 354,
                'product_id' => 165,
                'quantity' => 40,
                'tag' => '',
                'unit_cost' => '7.27',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 355,
                'product_id' => 97,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2025-02-01',
                'id' => 356,
                'product_id' => 199,
                'quantity' => 42,
                'tag' => '110002',
                'unit_cost' => '7.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 357,
                'product_id' => 130,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '37.32',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 358,
                'product_id' => 110,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-03-30',
                'id' => 359,
                'product_id' => 137,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '44.27',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 360,
                'product_id' => 34,
                'quantity' => 439,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 361,
                'product_id' => 59,
                'quantity' => 60,
                'tag' => '',
                'unit_cost' => '11.43',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-23',
                'id' => 362,
                'product_id' => 58,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '11.44',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 363,
                'product_id' => 194,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-30',
                'id' => 364,
                'product_id' => 10,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-04-27',
                'id' => 365,
                'product_id' => 28,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '7.58',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 366,
                'product_id' => 6,
                'quantity' => 65,
                'tag' => 'OP312',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 367,
                'product_id' => 15,
                'quantity' => 60,
                'tag' => '1101',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 368,
                'product_id' => 102,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '7.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 369,
                'product_id' => 193,
                'quantity' => 116,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 370,
                'product_id' => 84,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 371,
                'product_id' => 20,
                'quantity' => 19,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-09',
                'id' => 372,
                'product_id' => 45,
                'quantity' => 27,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 373,
                'product_id' => 136,
                'quantity' => 28,
                'tag' => '',
                'unit_cost' => '37.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 374,
                'product_id' => 186,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 375,
                'product_id' => 147,
                'quantity' => 240,
                'tag' => '',
                'unit_cost' => '5.90',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-01-01',
                'id' => 376,
                'product_id' => 146,
                'quantity' => 40,
                'tag' => '',
                'unit_cost' => '5.86',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 377,
                'product_id' => 86,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 378,
                'product_id' => 113,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-04-30',
                'id' => 379,
                'product_id' => 136,
                'quantity' => 14,
                'tag' => '',
                'unit_cost' => '36.95',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-06-23',
                'id' => 380,
                'product_id' => 57,
                'quantity' => 33,
                'tag' => '',
                'unit_cost' => '11.64',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-09-01',
                'id' => 381,
                'product_id' => 150,
                'quantity' => 18,
                'tag' => '192165',
                'unit_cost' => '6.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-02',
                'id' => 382,
                'product_id' => 188,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 383,
                'product_id' => 144,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 384,
                'product_id' => 45,
                'quantity' => 61,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-26',
                'id' => 385,
                'product_id' => 62,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-22',
                'id' => 386,
                'product_id' => 123,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '5.81',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-10',
                'id' => 387,
                'product_id' => 19,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '12.94',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 388,
                'product_id' => 134,
                'quantity' => 51,
                'tag' => '',
                'unit_cost' => '37.03',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 389,
                'product_id' => 112,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 390,
                'product_id' => 46,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '1.55',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 391,
                'product_id' => 18,
                'quantity' => 32,
                'tag' => '',
                'unit_cost' => '12.95',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 392,
                'product_id' => 103,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 393,
                'product_id' => 34,
                'quantity' => 70,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-09',
                'id' => 394,
                'product_id' => 52,
                'quantity' => 46,
                'tag' => '',
                'unit_cost' => '0.88',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 395,
                'product_id' => 98,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 396,
                'product_id' => 121,
                'quantity' => 70,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 397,
                'product_id' => 38,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '0.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 398,
                'product_id' => 80,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-14',
                'id' => 399,
                'product_id' => 114,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 400,
                'product_id' => 131,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '37.05',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 401,
                'product_id' => 143,
                'quantity' => 54,
                'tag' => '',
                'unit_cost' => '6.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 402,
                'product_id' => 200,
                'quantity' => 111,
                'tag' => '',
                'unit_cost' => '13.09',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 403,
                'product_id' => 86,
                'quantity' => 22,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 404,
                'product_id' => 93,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 405,
                'product_id' => 161,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-18',
                'id' => 406,
                'product_id' => 3,
                'quantity' => 25,
                'tag' => '',
                'unit_cost' => '8.02',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 407,
                'product_id' => 161,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 408,
                'product_id' => 38,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '0.66',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-06',
                'id' => 409,
                'product_id' => 129,
                'quantity' => 112,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 410,
                'product_id' => 105,
                'quantity' => 60,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 411,
                'product_id' => 44,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '1.24',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-02',
                'id' => 412,
                'product_id' => 195,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 413,
                'product_id' => 43,
                'quantity' => 36,
                'tag' => '',
                'unit_cost' => '1.28',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 414,
                'product_id' => 112,
                'quantity' => 48,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-03-10',
                'id' => 415,
                'product_id' => 43,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '1.28',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 416,
                'product_id' => 117,
                'quantity' => 34,
                'tag' => '0281760',
                'unit_cost' => '18.00',
                'warehouse' => 80,
            ],

            [
                'expiry' => '2023-07-30',
                'id' => 417,
                'product_id' => 82,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '15.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 418,
                'product_id' => 5,
                'quantity' => 6,
                'tag' => '0P311',
                'unit_cost' => '4.25',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 419,
                'product_id' => 101,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 420,
                'product_id' => 64,
                'quantity' => 276,
                'tag' => '',
                'unit_cost' => '6.18',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-02-01',
                'id' => 421,
                'product_id' => 189,
                'quantity' => 2,
                'tag' => '9B201',
                'unit_cost' => '9.62',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 422,
                'product_id' => 71,
                'quantity' => 123,
                'tag' => '',
                'unit_cost' => '6.16',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 423,
                'product_id' => 137,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '44.27',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-19',
                'id' => 424,
                'product_id' => 120,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '5.81',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 425,
                'product_id' => 149,
                'quantity' => 49,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 426,
                'product_id' => 147,
                'quantity' => 90,
                'tag' => '',
                'unit_cost' => '5.89',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 427,
                'product_id' => 155,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 428,
                'product_id' => 5,
                'quantity' => 105,
                'tag' => 'OP311',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 429,
                'product_id' => 60,
                'quantity' => 20,
                'tag' => '',
                'unit_cost' => '11.54',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 430,
                'product_id' => 136,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '36.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-17',
                'id' => 431,
                'product_id' => 93,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 432,
                'product_id' => 71,
                'quantity' => 188,
                'tag' => '',
                'unit_cost' => '6.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 433,
                'product_id' => 164,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '22.19',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 434,
                'product_id' => 24,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '15.47',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 435,
                'product_id' => 81,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 436,
                'product_id' => 164,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '22.19',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 437,
                'product_id' => 104,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 438,
                'product_id' => 195,
                'quantity' => 90,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 439,
                'product_id' => 66,
                'quantity' => 84,
                'tag' => '',
                'unit_cost' => '6.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 440,
                'product_id' => 144,
                'quantity' => 78,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 441,
                'product_id' => 24,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '16.19',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-26',
                'id' => 442,
                'product_id' => 91,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 443,
                'product_id' => 162,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-09',
                'id' => 444,
                'product_id' => 47,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '1.73',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 445,
                'product_id' => 88,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-02-04',
                'id' => 446,
                'product_id' => 61,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-09-14',
                'id' => 447,
                'product_id' => 115,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 448,
                'product_id' => 65,
                'quantity' => 216,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 449,
                'product_id' => 133,
                'quantity' => 22,
                'tag' => '',
                'unit_cost' => '38.75',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 450,
                'product_id' => 84,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 451,
                'product_id' => 16,
                'quantity' => 12,
                'tag' => '113980I102',
                'unit_cost' => '4.25',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-04-01',
                'id' => 452,
                'product_id' => 73,
                'quantity' => 24,
                'tag' => '0811910',
                'unit_cost' => '20.40',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-02-01',
                'id' => 453,
                'product_id' => 53,
                'quantity' => 13,
                'tag' => '26986',
                'unit_cost' => '6.74',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 454,
                'product_id' => 179,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 455,
                'product_id' => 194,
                'quantity' => 88,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 456,
                'product_id' => 109,
                'quantity' => 10,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 457,
                'product_id' => 156,
                'quantity' => 94,
                'tag' => '',
                'unit_cost' => '9.98',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-09-01',
                'id' => 458,
                'product_id' => 150,
                'quantity' => 209,
                'tag' => '1921650',
                'unit_cost' => '6.35',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-11-18',
                'id' => 459,
                'product_id' => 84,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 460,
                'product_id' => 145,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '5.89',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 461,
                'product_id' => 117,
                'quantity' => 11,
                'tag' => '0281760',
                'unit_cost' => '18.00',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 462,
                'product_id' => 65,
                'quantity' => 15,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 463,
                'product_id' => 123,
                'quantity' => 64,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-18',
                'id' => 464,
                'product_id' => 145,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '5.89',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 465,
                'product_id' => 192,
                'quantity' => 142,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 466,
                'product_id' => 70,
                'quantity' => 23,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-15',
                'id' => 467,
                'product_id' => 198,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '3.33',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 468,
                'product_id' => 9,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 469,
                'product_id' => 45,
                'quantity' => 52,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 470,
                'product_id' => 19,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '12.53',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 471,
                'product_id' => 70,
                'quantity' => 14,
                'tag' => '',
                'unit_cost' => '6.17',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 472,
                'product_id' => 122,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '5.55',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 473,
                'product_id' => 64,
                'quantity' => 102,
                'tag' => '',
                'unit_cost' => '6.18',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 474,
                'product_id' => 148,
                'quantity' => 260,
                'tag' => '',
                'unit_cost' => '5.88',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 475,
                'product_id' => 4,
                'quantity' => 6,
                'tag' => '0P282',
                'unit_cost' => '4.25',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 476,
                'product_id' => 124,
                'quantity' => 67,
                'tag' => '',
                'unit_cost' => '7.41',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 477,
                'product_id' => 90,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-01',
                'id' => 478,
                'product_id' => 16,
                'quantity' => 43,
                'tag' => '01102',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 479,
                'product_id' => 25,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '12.70',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 480,
                'product_id' => 161,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '33.29',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 481,
                'product_id' => 69,
                'quantity' => 84,
                'tag' => '',
                'unit_cost' => '6.32',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 482,
                'product_id' => 191,
                'quantity' => 70,
                'tag' => '',
                'unit_cost' => '9.62',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-04-01',
                'id' => 483,
                'product_id' => 62,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 484,
                'product_id' => 191,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '9.62',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 485,
                'product_id' => 96,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-09-15',
                'id' => 486,
                'product_id' => 183,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 487,
                'product_id' => 82,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 488,
                'product_id' => 83,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '16.65',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 489,
                'product_id' => 155,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '13.76',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 490,
                'product_id' => 188,
                'quantity' => 51,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2022-10-22',
                'id' => 491,
                'product_id' => 108,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 492,
                'product_id' => 136,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '36.95',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 493,
                'product_id' => 20,
                'quantity' => 25,
                'tag' => '',
                'unit_cost' => '12.32',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-04-01',
                'id' => 494,
                'product_id' => 199,
                'quantity' => 5,
                'tag' => '100226',
                'unit_cost' => '7.10',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 495,
                'product_id' => 91,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 496,
                'product_id' => 68,
                'quantity' => 156,
                'tag' => '',
                'unit_cost' => '6.18',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-10-28',
                'id' => 497,
                'product_id' => 146,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-05-01',
                'id' => 498,
                'product_id' => 165,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '6.90',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-11',
                'id' => 499,
                'product_id' => 143,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '5.84',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 500,
                'product_id' => 143,
                'quantity' => 142,
                'tag' => '',
                'unit_cost' => '6.00',
                'warehouse' => 4,
            ],
        ]);
        DB::table('as400_warehouse_stock')->insert([

            [
                'expiry' => '2023-10-26',
                'id' => 501,
                'product_id' => 27,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 502,
                'product_id' => 133,
                'quantity' => 16,
                'tag' => '',
                'unit_cost' => '37.12',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 503,
                'product_id' => 64,
                'quantity' => 190,
                'tag' => '',
                'unit_cost' => '6.15',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 504,
                'product_id' => 92,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-30',
                'id' => 505,
                'product_id' => 191,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '9.62',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-08-12',
                'id' => 506,
                'product_id' => 60,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '11.68',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-01-08',
                'id' => 507,
                'product_id' => 100,
                'quantity' => 8,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 508,
                'product_id' => 52,
                'quantity' => 186,
                'tag' => '',
                'unit_cost' => '0.88',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-07-23',
                'id' => 509,
                'product_id' => 125,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '7.76',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-05-01',
                'id' => 510,
                'product_id' => 30,
                'quantity' => 4691,
                'tag' => '0E112',
                'unit_cost' => '4.25',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-12-11',
                'id' => 511,
                'product_id' => 142,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '534.23',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 512,
                'product_id' => 130,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '36.91',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-06-01',
                'id' => 513,
                'product_id' => 144,
                'quantity' => 212,
                'tag' => '',
                'unit_cost' => '6.06',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 514,
                'product_id' => 136,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '37.65',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-03-01',
                'id' => 515,
                'product_id' => 29,
                'quantity' => 159,
                'tag' => '0012510',
                'unit_cost' => '7.50',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 516,
                'product_id' => 193,
                'quantity' => 271,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-05-05',
                'id' => 517,
                'product_id' => 148,
                'quantity' => 49,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 518,
                'product_id' => 87,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '11.10',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-01',
                'id' => 519,
                'product_id' => 190,
                'quantity' => 7,
                'tag' => '0A281',
                'unit_cost' => '14.43',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 520,
                'product_id' => 106,
                'quantity' => 131,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 521,
                'product_id' => 48,
                'quantity' => 24,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-07-01',
                'id' => 522,
                'product_id' => 21,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '12.30',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 523,
                'product_id' => 131,
                'quantity' => 19,
                'tag' => '',
                'unit_cost' => '37.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-02-01',
                'id' => 524,
                'product_id' => 77,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 525,
                'product_id' => 70,
                'quantity' => 144,
                'tag' => '',
                'unit_cost' => '6.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 526,
                'product_id' => 154,
                'quantity' => 31,
                'tag' => '',
                'unit_cost' => '10.54',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 527,
                'product_id' => 48,
                'quantity' => 25,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-07-01',
                'id' => 528,
                'product_id' => 49,
                'quantity' => 32,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 529,
                'product_id' => 195,
                'quantity' => 135,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-02',
                'id' => 530,
                'product_id' => 104,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-06-10',
                'id' => 531,
                'product_id' => 133,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '36.98',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-03-11',
                'id' => 532,
                'product_id' => 63,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '51.66',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 533,
                'product_id' => 35,
                'quantity' => 17,
                'tag' => '',
                'unit_cost' => '5.17',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-08-01',
                'id' => 534,
                'product_id' => 4,
                'quantity' => 50,
                'tag' => 'OP282',
                'unit_cost' => '4.25',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 535,
                'product_id' => 45,
                'quantity' => 270,
                'tag' => '',
                'unit_cost' => '1.33',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 536,
                'product_id' => 194,
                'quantity' => 215,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-09-01',
                'id' => 537,
                'product_id' => 120,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '5.54',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-03',
                'id' => 538,
                'product_id' => 48,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-09-01',
                'id' => 539,
                'product_id' => 150,
                'quantity' => 10,
                'tag' => '1921650',
                'unit_cost' => '6.35',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-12-17',
                'id' => 540,
                'product_id' => 77,
                'quantity' => 4,
                'tag' => '',
                'unit_cost' => '11.07',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-01-01',
                'id' => 541,
                'product_id' => 140,
                'quantity' => 17,
                'tag' => '100015',
                'unit_cost' => '26.19',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 542,
                'product_id' => 125,
                'quantity' => 21,
                'tag' => '',
                'unit_cost' => '7.42',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 543,
                'product_id' => 46,
                'quantity' => 6,
                'tag' => '',
                'unit_cost' => '1.55',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-07-03',
                'id' => 544,
                'product_id' => 50,
                'quantity' => 5,
                'tag' => '',
                'unit_cost' => '1.11',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2022-10-30',
                'id' => 545,
                'product_id' => 149,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '5.87',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-04-01',
                'id' => 546,
                'product_id' => 17,
                'quantity' => 211,
                'tag' => '110006',
                'unit_cost' => '3.05',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-09-24',
                'id' => 547,
                'product_id' => 200,
                'quantity' => 30,
                'tag' => '',
                'unit_cost' => '12.92',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-07',
                'id' => 548,
                'product_id' => 106,
                'quantity' => 7,
                'tag' => '',
                'unit_cost' => '4.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 549,
                'product_id' => 148,
                'quantity' => 102,
                'tag' => '',
                'unit_cost' => '5.92',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-06-01',
                'id' => 550,
                'product_id' => 191,
                'quantity' => 11,
                'tag' => '',
                'unit_cost' => '9.62',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-11-09',
                'id' => 551,
                'product_id' => 187,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2024-10-01',
                'id' => 552,
                'product_id' => 196,
                'quantity' => 68,
                'tag' => '',
                'unit_cost' => '3.70',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2024-11-01',
                'id' => 553,
                'product_id' => 80,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '6.63',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-11-01',
                'id' => 554,
                'product_id' => 188,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '2.59',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2024-08-01',
                'id' => 555,
                'product_id' => 181,
                'quantity' => 18,
                'tag' => '',
                'unit_cost' => '3.10',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2023-06-11',
                'id' => 556,
                'product_id' => 26,
                'quantity' => 2,
                'tag' => '',
                'unit_cost' => '10.35',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 557,
                'product_id' => 127,
                'quantity' => 21,
                'tag' => '',
                'unit_cost' => '8.00',
                'warehouse' => 8,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 558,
                'product_id' => 68,
                'quantity' => 3,
                'tag' => '',
                'unit_cost' => '6.16',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 559,
                'product_id' => 99,
                'quantity' => 1,
                'tag' => '',
                'unit_cost' => '7.21',
                'warehouse' => 4,
            ],

            [
                'expiry' => '2022-04-01',
                'id' => 560,
                'product_id' => 131,
                'quantity' => 9,
                'tag' => '',
                'unit_cost' => '37.05',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-10-01',
                'id' => 561,
                'product_id' => 7,
                'quantity' => 12,
                'tag' => '',
                'unit_cost' => '7.39',
                'warehouse' => 1,
            ],

            [
                'expiry' => '2023-01-09',
                'id' => 562,
                'product_id' => 132,
                'quantity' => 68,
                'tag' => '',
                'unit_cost' => '36.97',
                'warehouse' => 1,
            ],
        ]);
    }
}
