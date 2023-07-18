<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('regulatory_info', function (Blueprint $table) {
            $table->string('serving_size')->change();
        });
    }

    public function down()
    {
    }
};
