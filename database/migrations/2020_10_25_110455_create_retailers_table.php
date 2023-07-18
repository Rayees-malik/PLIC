<?php

use App\Helpers\SignoffStateHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('retailers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->integer('number_stores')->nullable();
            $table->date('fiscal_year_start')->nullable();

            $table->unsignedBigInteger('account_manager_id')->nullable();
            $table->foreign('account_manager_id')->references('id')->on('users');

            $table->text('distribution_type')->nullable();

            $table->decimal('markup', 5, 2)->nullable();
            $table->decimal('target_margin', 5, 2)->nullable();
            $table->string('as400_pricing_file')->nullable();

            $table->string('costing_type')->default('landed');
            $table->string('warehouse_number')->default('01');

            //$table->string('category_data')->nullable(); // Unsure if this is required, hidden for now
            $table->boolean('allow_promos')->default(false);
            $table->json('websites')->nullable();

            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('retailers');
    }
};
