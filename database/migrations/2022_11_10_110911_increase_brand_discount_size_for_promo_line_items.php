<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promo_line_items', function (Blueprint $table) {
            $table->decimal('brand_discount', 6, 2)->change();
        });
    }
};
