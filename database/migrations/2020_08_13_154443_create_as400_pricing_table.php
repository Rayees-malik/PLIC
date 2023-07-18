<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_pricing', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->decimal('wholesale_price', 8, 2);
            $table->decimal('landed_cost', 8, 2);
            $table->decimal('duty', 5, 2);
            $table->decimal('edlp_discount', 5, 2);

            $table->decimal('po_price', 8, 2); // vendor currency, convert?
            $table->decimal('next_po_price', 8, 2)->nullable(); // vendor currency, convert?
            $table->date('po_price_expiry')->nullable();

            $table->boolean('taxable')->default(false);
        });
    }
};
