<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('signoff_config_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('signoff_config_id');
            $table->foreign('signoff_config_id')->references('id')->on('signoff_config');
            $table->unsignedInteger('step');
            $table->string('name')->nullable();
            $table->string('form_request')->nullable();
            $table->string('form_view');
            $table->unsignedInteger('signoffs_required')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signoff_config_steps');
    }
};
