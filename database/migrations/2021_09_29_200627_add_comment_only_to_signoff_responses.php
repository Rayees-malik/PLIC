<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('signoff_responses', function (Blueprint $table) {
            $table->boolean('comment_only')->default(false);
        });
    }

    public function down()
    {
        Schema::table('signoff_responses', function (Blueprint $table) {
            $table->dropColumn('comment_only');
        });
    }
};
