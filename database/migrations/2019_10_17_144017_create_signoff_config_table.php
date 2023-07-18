<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('signoff_config', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model');
            $table->string('show_route');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signoff_config');
    }
};
