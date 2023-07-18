<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('signoffs', function (Blueprint $table) {
            $table->unsignedBigInteger('signoff_config_id');
            $table->foreign('signoff_config_id')->references('id')->on('signoff_config');
        });
    }

    public function down()
    {
        Schema::table('signoffs', function (Blueprint $table) {
            $table->dropForeign('signoffs_signoff_config_id_foreign');
            $table->dropColumn('signoff_config_id');
        });
    }
};
