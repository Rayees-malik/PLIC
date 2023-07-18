<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $distributors = [
            ['name' => 'K&F', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'McKesson', 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Matrix', 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('distributors')->insert($distributors);
    }
}
