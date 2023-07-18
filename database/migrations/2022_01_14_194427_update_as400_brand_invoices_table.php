<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('as400_brand_invoices', function (Blueprint $table) {
            $table->decimal('discount_amount', 9, 2)->default(0)->change();
        });
    }

    public function down()
    {
        //
    }
};
