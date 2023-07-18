<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehousesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $warehouses = [
            ['name' => 'Acton', 'number' => '01', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'QC Acton', 'number' => '50', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Vancouver', 'number' => '04', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'QC Vancouver', 'number' => '40', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Calgary', 'number' => '08', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'QC Calgary', 'number' => '80', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('warehouses')->insert($warehouses);
    }
}
