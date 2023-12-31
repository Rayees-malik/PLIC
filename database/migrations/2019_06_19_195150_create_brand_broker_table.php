<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brand_broker', function (Blueprint $table) {
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('broker_id')->nullable();
            $table->foreign('broker_id')->references('id')->on('brokers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_broker');
    }
};
