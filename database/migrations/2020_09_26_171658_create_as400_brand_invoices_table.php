<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_brand_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->string('cheque_number');
            $table->string('invoice_number');
            $table->date('voucher_date')->nullable();
            $table->date('invoice_date')->nullable();

            $table->string('reference');
            $table->decimal('invoice_amount', 9, 2);
            $table->decimal('discount_amount', 9, 2);
        });
    }
};
