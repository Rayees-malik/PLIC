<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('as400_supersedes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('superseded_id');
            $table->foreign('superseded_id')->references('id')->on('products');

            $table->unsignedBigInteger('superseding_id');
            $table->foreign('superseding_id')->references('id')->on('products');
        });
    }
};
