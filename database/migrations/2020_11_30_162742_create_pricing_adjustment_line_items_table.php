<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricing_adjustment_line_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('pricing_adjustment_id')->nullable();
            $table->foreign('pricing_adjustment_id')->references('id')->on('pricing_adjustments');

            $table->morphs('item');

            $table->decimal('total_discount', 5, 2)->nullable();
            $table->string('who_to_mcb')->nullable();
            $table->decimal('total_mcb', 5, 2)->nullable();

            $table->unsignedBigInteger('cloned_from_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_adjustment_line_items');
    }
};
