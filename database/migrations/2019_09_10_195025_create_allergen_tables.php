<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('allergen_product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('allergen_id')->nullable();
            $table->foreign('allergen_id')->references('id')->on('allergens');
            $table->integer('contains')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists([
            'allergens',
            'product_allergen',
        ]);
    }
};
