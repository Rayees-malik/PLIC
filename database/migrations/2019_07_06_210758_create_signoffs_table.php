<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('signoffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->morphs('initial');
            $table->morphs('proposed');
            $table->unsignedInteger('step')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->boolean('new_submission')->default(false);
            $table->integer('state')->default(5);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signoffs');
    }
};
