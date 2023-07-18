<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('old_unit_cost', 8, 2)->nullable();
            $table->decimal('old_landed_cost', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('old_unit_cost');
            $table->dropColumn('old_landed_cost');
        });
    }
};
