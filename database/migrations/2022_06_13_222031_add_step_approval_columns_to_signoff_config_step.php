<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('signoff_config_steps', 'approval_to_type')) {
            Schema::table('signoff_config_steps', function (Blueprint $table) {
                $table->string('approval_to_type')->nullable();
            });
        }

        if (! Schema::hasColumn('signoff_config_steps', 'approval_to')) {
            Schema::table('signoff_config_steps', function (Blueprint $table) {
                $table->string('approval_to')->nullable();
            });
        }
    }

    public function down()
    {
        if (! Schema::hasColumn('signoff_config_steps', 'approval_to_type')) {
            Schema::table('signoff_config_steps', function (Blueprint $table) {
                $table->dropColumn('approval_to_type');
            });
        }

        if (! Schema::hasColumn('signoff_config_steps', 'approval_to')) {
            Schema::table('signoff_config_steps', function (Blueprint $table) {
                $table->dropColumn('approval_to');
            });
        }
    }
};
