<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('distributor_retailer', function (Blueprint $table) {
            $table->unsignedBigInteger('distributor_id');
            $table->foreign('distributor_id')->references('id')->on('distributors');
            $table->unsignedBigInteger('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers');
        });
    }

    public function down()
    {
        Schema::dropIfExists('distributor_retailer');
    }
};
