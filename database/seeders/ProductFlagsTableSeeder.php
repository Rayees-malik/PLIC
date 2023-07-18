<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductFlagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $productFlags = [
            ['name' => 'Display', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Holiday', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Summer', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Thanksgiving', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Christmas', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Paleo', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Keto', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('product_flags')->insert($productFlags);
    }
}
