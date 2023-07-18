<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('finance_brand_number')->nullable();
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('finance_brand_number');
        });
    }
};
