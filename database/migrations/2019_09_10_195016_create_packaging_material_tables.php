<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packaging_materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('packaging_material_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('packaging_material_id')->nullable();
            $table->foreign('packaging_material_id')->references('id')->on('packaging_materials');
        });
    }

    public function down()
    {
        Schema::dropIfExists(['packaging_material_product', 'product_sell_by_unit']);
    }
};
