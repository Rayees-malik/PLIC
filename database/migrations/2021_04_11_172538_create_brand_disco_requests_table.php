<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brand_disco_requests', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('submitted_by');
            $table->foreign('submitted_by')->references('id')->on('users');

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->string('name');

            $table->text('reason')->nullable();
            $table->text('recoup_plan')->nullable();

            $table->decimal('ap_owed', 9, 2)->nullable();
            $table->string('ra_number')->nullable();
            $table->text('return_address')->nullable();

            $table->decimal('ytd_sales', 9, 2)->nullable();
            $table->decimal('ytd_margin', 9, 2)->nullable();
            $table->decimal('previous_year_sales', 9, 2)->nullable();
            $table->decimal('previous_year_margin', 9, 2)->nullable();
            $table->decimal('inventory_value', 9, 2)->nullable();

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_disco_requests');
    }
};
