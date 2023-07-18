<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('inventory_writeoffs')) {
            Schema::table('inventory_writeoffs', function (Blueprint $table) {
                $table->dropForeign('inventory_writeoffs_submitted_by_foreign');
            });
        }

        if (Schema::hasTable('inventory_writeoff_line_items')) {
            Schema::table('inventory_writeoff_line_items', function (Blueprint $table) {
                $table->dropForeign('inventory_writeoff_line_items_inventory_writeoff_id_foreign');
                $table->dropForeign('inventory_writeoff_line_items_product_id_foreign');
            });
        }

        if (Schema::hasTable('inventory_writeoffs')) {
            Schema::rename('inventory_writeoffs', 'inventory_removals');
        }

        if (Schema::hasTable('inventory_writeoff_line_items')) {
            Schema::rename('inventory_writeoff_line_items', 'inventory_removal_line_items');
        }

        Schema::table('inventory_removals', function (Blueprint $table) {
            $table->foreign('submitted_by')->references('id')->on('users');
        });

        if (Schema::hasColumn('inventory_removal_line_items', 'inventory_writeoff_id')) {
            Schema::table('inventory_removal_line_items', function (Blueprint $table) {
                $table->renameColumn('inventory_writeoff_id', 'inventory_removal_id');
                $table->foreign('inventory_removal_id')->references('id')->on('inventory_removals');
            });
        }

        Schema::table('inventory_removal_line_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    public function down()
    {
        // Something we should never be doing so not worth the effort
        dd('Cannot reverse.');
    }
};
