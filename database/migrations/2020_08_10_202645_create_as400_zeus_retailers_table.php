<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_zeus_retailers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('invoice_date')->nullable();
            $table->string('category')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
        });
    }
};
