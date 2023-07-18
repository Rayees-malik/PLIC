<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagingMaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $materials = [
            ['name' => 'Newsprint', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Magazines', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Directories', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Printed Paper', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Corrugate', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Gabletop', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Paper Laminants', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Aseptic Containers', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Boxboard', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'General Use Paper', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'PET', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'HDPE', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Plastic Film', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Plastic Laminants', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Polystyrene Foam', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Other Plastic', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Food and Beverage', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Aerosols', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Other Steel', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Aluminum Cans', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Aluminum Foil', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Flint Glass', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Coloured Glass', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('packaging_materials')->insert($materials);
    }
}
