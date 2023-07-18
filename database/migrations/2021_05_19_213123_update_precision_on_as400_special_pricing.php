<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('as400_special_pricing', function (Blueprint $table) {
            $table->decimal('percent_discount', 6, 3)->change();
        });
    }

    public function down(): void
    {
        Schema::table('as400_special_pricing', function (Blueprint $table) {
            $table->decimal('percent_discount', 5, 2)->change();
        });
    }
};
