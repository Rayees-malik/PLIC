<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('type');
            $table->decimal('width', 8, 3)->nullable();
            $table->decimal('depth', 8, 3)->nullable();
            $table->decimal('height', 8, 3)->nullable();
            $table->decimal('gross_weight', 8, 3)->nullable();
            $table->decimal('net_weight', 8, 3)->nullable();

            $table->unsignedBigInteger('cloned_from_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dimensions');
    }
};
