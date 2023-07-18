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
        Schema::create('brands', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('vendors')->nullable();

            $table->string('name')->nullable();
            $table->string('name_fr')->nullable();
            $table->boolean('made_in_canada')->default(false);
            $table->string('brand_number')->nullable();
            $table->string('category_code')->nullable();
            $table->text('broker_proposal')->nullable();
            $table->unsignedBigInteger('currency_id')->default(1);
            $table->foreign('currency_id')->references('id')->on('currencies')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_fr')->nullable();
            $table->longText('unpublished_new_listing_deal')->nullable();
            $table->longText('unpublished_new_listing_deal_fr')->nullable();
            $table->text('catalogue_notice')->nullable();
            $table->text('catalogue_notice_fr')->nullable();

            $table->boolean('contract_exclusive')->default(false);
            $table->boolean('no_other_distributors')->default(false);
            $table->string('also_distributed_by')->nullable();
            $table->boolean('allows_amazon_resale')->default(false);
            $table->boolean('map_pricing')->default(false);
            $table->unsignedInteger('minimum_order_quantity')->nullable();
            $table->string('minimum_order_type')->default('$');
            $table->string('shipping_lead_time')->nullable();
            $table->string('product_availability')->nullable();

            $table->string('nutrition_house_payment_type')->nullable();
            $table->string('nutrition_house')->nullable();
            $table->string('nutrition_house_payment')->nullable();
            $table->decimal('nutrition_house_percentage', 5, 2)->nullable();
            $table->decimal('nutrition_house_purity_percentage', 5, 2)->nullable();
            $table->string('health_first_payment_type')->nullable();
            $table->string('health_first')->nullable();
            $table->string('health_first_payment')->nullable();
            $table->decimal('health_first_percentage', 5, 2)->nullable();
            $table->decimal('health_first_purity_percentage', 5, 2)->nullable();
            $table->boolean('allow_oi')->default(false);

            $table->decimal('default_pl_discount', 5, 2)->nullable();
            $table->unsignedBigInteger('purchasing_specialist_id')->nullable();
            $table->foreign('purchasing_specialist_id')->references('id')->on('users');
            $table->unsignedBigInteger('vendor_relations_specialist_id')->nullable();
            $table->foreign('vendor_relations_specialist_id')->references('id')->on('users');
            $table->boolean('in_house_brand')->default(false);
            $table->boolean('business_partner_program')->default(false);

            $table->boolean('hide_from_exports')->default(false);
            $table->integer('state')->default(SignoffStateHelper::IN_PROGRESS);
            $table->index('state');
            $table->integer('status')->default(StatusHelper::UNSUBMITTED);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('brands');
    }
};
