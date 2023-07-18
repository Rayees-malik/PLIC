<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit_of_measure', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit');
            $table->string('unit_fr');
            $table->string('description');
            $table->string('description_fr');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_of_measure');
    }
};
