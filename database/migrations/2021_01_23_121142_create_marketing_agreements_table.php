<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marketing_agreements', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');
            $table->unsignedBigInteger('send_to')->nullable();
            $table->foreign('send_to')->references('id')->on('users');

            $table->string('name')->nullable();
            $table->string('account')->nullable();
            $table->string('account_other')->nullable();

            $table->string('ship_to_number')->nullable();
            $table->string('retailer_invoice')->nullable();
            $table->text('comment')->nullable();
            $table->text('approval_email')->nullable();

            $table->decimal('tax_rate', 5, 2)->nullable();

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('marketing_agreements');
    }
};
