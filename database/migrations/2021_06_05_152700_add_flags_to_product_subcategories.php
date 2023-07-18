<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_subcategories', function (Blueprint $table) {
            $table->unsignedInteger('flags')->nullable();
        });
    }

    public function down()
    {
        Schema::table('product_subcategories', function (Blueprint $table) {
            $table->dropColumn('flags');
        });
    }
};
