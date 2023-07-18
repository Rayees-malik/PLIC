<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_stock_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('description');
            $table->string('category_code');

            $table->char('status', 1);
            $table->boolean('hide_from_catalogue')->default(false);
            $table->boolean('out_of_stock')->default(false);

            $table->date('last_received')->nullable();
            $table->date('expected')->nullable();
        });
    }
};
