<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_delist_requests', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->unsignedBigInteger('submitted_by');
            $table->foreign('submitted_by')->references('id')->on('users');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->text('reason')->nullable();
            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_delist_requests');
    }
};
