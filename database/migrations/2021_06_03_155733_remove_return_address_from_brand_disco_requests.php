<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brand_disco_requests', function (Blueprint $table) {
            $table->dropColumn('return_address');
            $table->dropColumn('ra_number');
        });
    }

    public function down()
    {
        Schema::table('brand_disco_requests', function (Blueprint $table) {
            $table->string('ra_number')->nullable();
            $table->text('return_address')->nullable();
        });
    }
};
