<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_removal_line_items', function (Blueprint $table) {
            $table->decimal('average_landed_cost', 8, 2)->after('cost')->nullable()->comment('Average landed cost of the product (comes from AS/400)');
        });
    }

    public function down()
    {
        Schema::table('inventory_removal_line_items', function (Blueprint $table) {
            $table->dropColumn('average_landed_cost');
        });
    }
};
