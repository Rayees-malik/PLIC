<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('as400_pricing', function (Blueprint $table) {
            $table->renameColumn('landed_cost', 'average_landed_cost');
        });
    }

    public function down()
    {
        Schema::table('as400_pricing', function (Blueprint $table) {
            $table->renameColumn('average_landed_cost', 'landed_cost');
        });
    }
};
