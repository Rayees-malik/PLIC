<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_special_pricing', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('priceable');

            $table->string('price_code');
            $table->decimal('price', 8, 2);
            $table->decimal('percent_discount', 5, 2);
            $table->date('start_date');
            $table->date('end_date');

            $table->string('extra')->nullable();
        });
    }
};
