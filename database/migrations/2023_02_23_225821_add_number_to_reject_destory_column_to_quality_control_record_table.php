<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quality_control_records', function (Blueprint $table) {
            $table->unsignedSmallInteger('number_to_reject_destroy')->nullable();
        });
    }

    public function down()
    {
        Schema::table('quality_control_records', function (Blueprint $table) {
            $table->dropColumn('number_to_reject_destroy');
        });
    }
};
