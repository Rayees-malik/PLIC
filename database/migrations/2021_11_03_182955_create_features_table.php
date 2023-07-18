<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('features', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('feature')->unique();
            $table->text('description')->nullable();
            $table->timestamp('active_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('features');
    }
};
