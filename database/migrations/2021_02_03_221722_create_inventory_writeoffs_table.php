<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_writeoffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');

            $table->string('name')->nullable();
            $table->text('comment')->nullable();

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_writeoffs');
    }
};
