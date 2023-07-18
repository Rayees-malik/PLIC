<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('royalty_percent');
            $table->dropColumn('royalty_dollar');
            $table->decimal('extra_addon_percent', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('royalty_percent', 5, 2)->nullable();
            $table->decimal('royalty_dollar', 5, 2)->nullable();
            $table->dropColumn('extra_addon_percent');
        });
    }
};
