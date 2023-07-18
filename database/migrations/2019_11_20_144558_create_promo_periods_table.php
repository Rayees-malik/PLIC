<?php

use App\Models\PromoPeriod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promo_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(true);
            $table->string('type')->default(PromoPeriod::CATALOGUE_TYPE);
            $table->unsignedBigInteger('base_period_id')->nullable();
            $table->foreign('base_period_id')->references('id')->on('promo_periods');
            $table->string('order_form_header')->nullable();
            $table->nullableMorphs('owner');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promo_periods');
    }
};
