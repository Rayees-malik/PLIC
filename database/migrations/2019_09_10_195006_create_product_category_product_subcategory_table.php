<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_category_product_subcategory', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id')->nullable();
            $table->foreign('product_category_id', 'pcpsc_category_id_foreign')->references('id')->on('product_categories');
            $table->unsignedBigInteger('product_subcategory_id')->nullable();
            $table->foreign('product_subcategory_id', 'pcpsc_subcategory_id_foreign')->references('id')->on('product_subcategories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_category_product_subcategory');
    }
};
