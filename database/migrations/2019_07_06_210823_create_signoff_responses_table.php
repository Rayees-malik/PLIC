<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('signoff_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('signoff_id');
            $table->foreign('signoff_id')->references('id')->on('signoffs')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('step');
            $table->boolean('approved');
            $table->boolean('archived')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('signoff_responses');
    }
};
