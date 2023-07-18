<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pricing_adjustments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');

            $table->string('name')->nullable();

            $table->json('accounts')->nullable();

            $table->boolean('ongoing')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('dollar_discount')->default(false);
            $table->boolean('dollar_mcb')->default(false);
            $table->boolean('bpp')->default(false);
            $table->boolean('shared_line')->default(false);
            $table->text('comment')->nullable();
            $table->text('notes')->nullable();

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pricing_adjustments');
    }
};
