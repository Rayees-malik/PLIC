<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_warehouse_stock', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('warehouse')->nullable();
            $table->decimal('unit_cost', 8, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->date('expiry')->nullable();
            $table->string('tag')->nullable();
        });
    }
};
