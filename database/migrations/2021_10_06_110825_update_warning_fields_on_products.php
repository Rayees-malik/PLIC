<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('indications');
            $table->dropColumn('indications_fr');
            $table->text('warnings')->nullable();
            $table->text('warnings_fr')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('indications')->nullable();
            $table->text('indications_fr')->nullable();
            $table->dropColumn('warnings');
            $table->dropColumn('warnings_fr');
        });
    }
};
