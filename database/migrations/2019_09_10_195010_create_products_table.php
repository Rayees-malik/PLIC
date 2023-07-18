<?php

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');

            // General
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->string('name')->nullable();
            $table->string('name_fr')->nullable();
            $table->string('stock_id')->nullable();
            $table->index('stock_id');
            $table->boolean('is_display')->default(false);
            $table->unsignedBigInteger('supersedes_id')->nullable();
            $table->foreign('supersedes_id')->references('id')->on('products');
            $table->unsignedBigInteger('country_origin')->nullable();
            $table->foreign('country_origin')->references('id')->on('countries');
            $table->unsignedBigInteger('country_shipped')->nullable();
            $table->foreign('country_shipped')->references('id')->on('countries');
            $table->string('tariff_code')->nullable();
            $table->string('packaging_language')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('product_categories');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->foreign('subcategory_id')->references('id')->on('product_subcategories');
            $table->unsignedBigInteger('catalogue_category_id')->nullable();
            $table->foreign('catalogue_category_id')->references('id')->on('catalogue_categories');
            $table->string('catalogue_category_proposal')->nullable();
            $table->string('catalogue_category_proposal_fr')->nullable();

            // Pricing
            $table->decimal('unit_cost', 8, 2)->nullable();
            $table->decimal('wholesale_price', 8, 2)->nullable();
            $table->decimal('landed_cost', 8, 2)->nullable();
            $table->boolean('not_for_resale')->default(false);
            $table->boolean('add_to_existing_casestack_deals')->default(false);
            $table->date('available_ship_date')->nullable();
            $table->unsignedInteger('minimum_order_units')->nullable();
            $table->decimal('royalty_dollar', 8, 2)->nullable();
            $table->decimal('royalty_percent', 5, 2)->nullable();
            $table->string('price_change_reason')->nullable();
            $table->date('price_change_date')->nullable();
            $table->decimal('temp_edlp', 5, 2)->nullable();
            $table->decimal('temp_duty', 5, 2)->nullable();

            // Packaging
            $table->unsignedInteger('purity_sell_by_unit')->nullable();
            $table->unsignedInteger('retailer_sell_by_unit')->nullable();
            $table->string('upc')->nullable();
            $table->integer('size')->nullable();
            $table->integer('uom_id')->nullable();
            $table->string('inner_upc')->nullable();
            $table->integer('inner_units')->nullable();
            $table->string('master_upc')->nullable();
            $table->integer('master_units')->nullable();
            $table->integer('cases_per_tie')->nullable();
            $table->integer('layers_per_skid')->nullable();

            // Details
            $table->boolean('tester_available')->nullable();
            $table->string('tester_brand_stock_id')->nullable();
            $table->string('brand_stock_id')->nullable();
            $table->text('description')->nullable();
            $table->text('description_fr')->nullable();
            $table->integer('shelf_life')->nullable();
            $table->text('shelf_life_units')->nullable();
            $table->text('features_1')->nullable();
            $table->text('features_2')->nullable();
            $table->text('features_3')->nullable();
            $table->text('features_4')->nullable();
            $table->text('features_5')->nullable();
            $table->text('features_fr_1')->nullable();
            $table->text('features_fr_2')->nullable();
            $table->text('features_fr_3')->nullable();
            $table->text('features_fr_4')->nullable();
            $table->text('features_fr_5')->nullable();
            $table->text('ingredients')->nullable();
            $table->text('ingredients_fr')->nullable();
            $table->text('indications')->nullable();
            $table->text('indications_fr')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('contraindications_fr')->nullable();
            $table->text('recommended_use')->nullable();
            $table->text('recommended_use_fr')->nullable();
            $table->text('recommended_dosage')->nullable();
            $table->text('recommended_dosage_fr')->nullable();
            $table->text('benefits')->nullable();
            $table->text('benefits_fr')->nullable();

            // Review
            $table->text('submission_notes')->nullable();

            // Misc Admin
            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS)->nullable();
            $table->index('state');
            $table->integer('status')->default(StatusHelper::UNSUBMITTED);
            $table->boolean('hide_flyer')->default(false);
            $table->boolean('hide_export')->default(false);
            $table->date('listed_on')->nullable();

            // Framework
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
