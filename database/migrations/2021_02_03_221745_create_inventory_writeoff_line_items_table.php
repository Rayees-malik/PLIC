<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_writeoff_line_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('inventory_writeoff_id')->nullable();
            $table->foreign('inventory_writeoff_id')->references('id')->on('inventory_writeoffs');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->decimal('cost', 8, 2);
            $table->integer('quantity')->nullable();
            $table->string('expiry')->nullable();
            $table->string('warehouse')->nullable();

            $table->boolean('full_mcb')->default(false);
            $table->boolean('reserve')->default(false);

            $table->text('reason')->nullable();

            $table->unsignedBigInteger('cloned_from_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_writeoff_line_items');
    }
};
