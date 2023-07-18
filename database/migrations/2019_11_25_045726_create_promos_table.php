<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');
            $table->string('name');
            $table->unsignedBigInteger('period_id');
            $table->foreign('period_id')->references('id')->on('promo_periods');
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->boolean('dollar_discount')->default(false);
            $table->boolean('line_drive')->default(false);
            $table->boolean('oi')->default(false);
            $table->boolean('oi_period_dates')->default(false);
            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);
            $table->json('data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promos');
    }
};
