<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regulatory_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            // Supplement / Bodycare (suncare only)
            $table->string('npn')->nullable(); // natural product number / can also be DIN-HM
            $table->date('npn_issued')->nullable();

            // Supplement
            $table->boolean('importer_is_purity')->default(false);
            $table->string('importer_name')->nullable();
            $table->string('importer_phone')->nullable();
            $table->string('importer_email')->nullable();

            // Food
            $table->string('serving_size')->nullable();
            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('total_fat', 8, 2)->nullable();
            $table->decimal('trans_fat', 8, 2)->nullable();
            $table->decimal('saturated_fat', 8, 2)->nullable();
            $table->decimal('cholesterol', 8, 2)->nullable();
            $table->decimal('sodium', 8, 2)->nullable();
            $table->decimal('carbohydrates', 8, 2)->nullable();
            $table->decimal('fiber', 8, 2)->nullable();
            $table->decimal('sugar', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();

            // Bodycare
            $table->string('cosmetic_notification_number')->nullable();

            // Medical Device
            $table->string('medical_class')->nullable(); // I / II
            $table->string('medical_device_establishment_id')->nullable();
            $table->string('medical_device_establishment_license_id')->nullable();

            // Pesticides
            $table->string('pesticide_class')->nullable(); // V / VI
            $table->string('pca_number')->nullable();

            $table->unsignedBigInteger('cloned_from_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regulatory_info');
    }
};
