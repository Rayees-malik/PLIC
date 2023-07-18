<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_gl_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('customer_number');
            $table->string('gl_account');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_gl_accounts');
    }
};
