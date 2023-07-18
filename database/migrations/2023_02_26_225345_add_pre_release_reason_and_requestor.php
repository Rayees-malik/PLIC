<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quality_control_records', function (Blueprint $table) {
            $table->string('pre_release_reason')->nullable();
            $table->string('pre_release_requested_by')->nullable();
        });
    }

    public function down()
    {
        Schema::table('quality_control_records', function (Blueprint $table) {
            $table->dropColumn('pre_release_reason');
            $table->dropColumn('pre_release_requested_by');
        });
    }
};
