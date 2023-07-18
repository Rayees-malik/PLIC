<?php

namespace Database\Seeders\Development\iseed;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class As400MarginsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('as400_margins')->delete();
    }
}
