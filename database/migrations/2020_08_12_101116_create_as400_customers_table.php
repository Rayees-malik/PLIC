<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_customers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('customer_number');
            $table->index('customer_number');

            $table->string('name');
            $table->string('province');
            $table->string('price_code');
        });
    }
};
