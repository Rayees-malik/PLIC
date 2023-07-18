<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('as400_pricing', function (Blueprint $table) {
            $table->decimal('extra_addon_percent', 5, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('as400_pricing', function (Blueprint $table) {
            $table->dropColumn('extra_addon_percent');
        });
    }
};
