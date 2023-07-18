<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_removals', function (Blueprint $table) {
            $table->unsignedBigInteger('cloned_from_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('inventory_removals', function (Blueprint $table) {
            $table->dropColumn('cloned_from_id');
        });
    }
};
