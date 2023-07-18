<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('as400_warehouse_stock', function (Blueprint $table) {
            $table->decimal('average_landed_cost', 8, 2)->after('unit_cost')->nullable()->comment('Average landed cost of the product in the warehouse');
        });
    }

    public function down()
    {
        Schema::table('as400_warehouse_stock', function (Blueprint $table) {
            $table->dropColumn('average_landed_cost');
        });
    }
};
