<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CertificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = new DateTime;
        $certs = [
            ['name' => 'Organic', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'GMO Free', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Vegetarian', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Vegan', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Fair Trade', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Kosher', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Halal', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'Gluten Free', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['name' => 'B Corporation Certification', 'requires_documentation' => false, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ];

        DB::table('certifications')->insert($certs);
    }
}
