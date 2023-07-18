<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_writeoff_line_items', function (Blueprint $table) {
            $table->renameColumn('reason', 'notes');
        });
        Schema::table('inventory_writeoff_line_items', function (Blueprint $table) {
            $table->string('reason')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventory_writeoff_line_items', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('inventory_writeoff_line_items', function (Blueprint $table) {
            $table->renameColumn('notes', 'reason');
        });
    }
};
