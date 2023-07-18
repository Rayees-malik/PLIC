<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_flags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_product_flag', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('product_flag_id')->nullable();
            $table->foreign('product_flag_id')->references('id')->on('product_flags');
        });
    }

    public function down()
    {
        Schema::dropIfExists(['product_product_flag', 'product_flags']);
    }
};
