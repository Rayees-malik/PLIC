<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $allergens = [
            ['name' => 'Egg', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Dairy', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Mustard', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Peanuts', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Seafood', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Sesame', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Soy', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Sulfites', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Tree Nuts', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Wheat Gluten', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('allergens')->insert($allergens);
    }
}
