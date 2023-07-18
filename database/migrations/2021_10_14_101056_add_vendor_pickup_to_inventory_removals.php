<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_removals', function (Blueprint $table) {
            $table->boolean('vendor_pickup')->default(false);
        });

        Schema::table('inventory_removal_line_items', function (Blueprint $table) {
            $table->boolean('vendor_pickup')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_removals', function (Blueprint $table) {
            $table->dropColumn('vendor_pickup');
        });

        Schema::table('inventory_removal_line_items', function (Blueprint $table) {
            $table->dropColumn('vendor_pickup');
        });
    }
};
