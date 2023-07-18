<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('original_vendor_id', 'cloned_from_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('cloned_from_id')->nullable();
        });

        Schema::table('pricing_adjustments', function (Blueprint $table) {
            $table->unsignedBigInteger('cloned_from_id')->nullable();
        });

        Schema::table('marketing_agreements', function (Blueprint $table) {
            $table->unsignedBigInteger('cloned_from_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->renameColumn('cloned_from_id', 'original_vendor_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('cloned_from_id');
        });

        Schema::table('pricing_adjustments', function (Blueprint $table) {
            $table->dropColumn('cloned_from_id');
        });

        Schema::table('marketing_agreements', function (Blueprint $table) {
            $table->dropColumn('cloned_from_id');
        });
    }
};
