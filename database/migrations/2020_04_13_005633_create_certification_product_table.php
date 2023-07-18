<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certification_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('certification_id')->nullable();
            $table->foreign('certification_id')->references('id')->on('certifications');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certification_product');
    }
};
