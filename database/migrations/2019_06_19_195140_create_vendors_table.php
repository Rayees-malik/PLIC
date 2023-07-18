<?php

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('who_to_mcb')->nullable();
            $table->string('cheque_payable_to')->nullable();
            $table->string('payment_terms')->nullable();
            $table->text('special_shipping_requirements')->nullable();
            $table->text('backorder_policy')->nullable();
            $table->text('return_policy')->nullable();
            $table->boolean('fob_purity_distribution_centres')->default(true);
            $table->boolean('consignment')->default(false);

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);
            $table->index('state');
            $table->integer('status')->default(StatusHelper::UNSUBMITTED);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
};
