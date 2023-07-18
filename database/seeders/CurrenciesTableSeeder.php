<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $currencies = [
            ['name' => 'CAD', 'exchange_rate' => '1', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'USD', 'exchange_rate' => '1.37', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'GBP', 'exchange_rate' => '1.83', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'EUR', 'exchange_rate' => '1.6', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'AUD', 'exchange_rate' => '1.05', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
