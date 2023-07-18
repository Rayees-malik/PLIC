<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retailer_listings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->json('data')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retailer_listings');
    }
};
