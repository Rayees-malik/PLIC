<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketing_agreement_line_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('marketing_agreement_id')->nullable();
            $table->foreign('marketing_agreement_id')->references('id')->on('marketing_agreements');

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->string('activity')->nullable();
            $table->string('promo_dates')->nullable();
            $table->decimal('cost', 9, 2)->nullable();
            $table->decimal('mcb_amount', 9, 2)->nullable();

            $table->unsignedBigInteger('cloned_from_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketing_agreement_line_items');
    }
};
