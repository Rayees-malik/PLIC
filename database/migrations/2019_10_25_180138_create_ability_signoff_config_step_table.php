<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ability_signoff_config_step', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('signoff_config_step_id');
            $table->foreign('signoff_config_step_id')->references('id')->on('signoff_config_steps');
            $table->unsignedInteger('ability_id');
            $table->foreign('ability_id')->references('id')->on('abilities');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ability_signoff_config_step');
    }
};
