<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('case_stack_deals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->unsignedBigInteger('period_id')->nullable();
            $table->foreign('period_id')->references('id')->on('promo_periods');

            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->foreign('last_updated_by')->references('id')->on('users');

            $table->text('deal')->nullable();
            $table->text('deal_fr')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('case_stack_deals');
    }
};
