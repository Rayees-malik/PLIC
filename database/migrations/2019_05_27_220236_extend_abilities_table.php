<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('abilities', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('abilities', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('description');
        });
    }
};
