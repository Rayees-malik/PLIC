<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_brand_po_received', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->string('po_number');
            $table->date('po_date')->nullable();
            $table->string('status');
        });
    }
};
