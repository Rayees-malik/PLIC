<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upcoming_changes', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('description', 255)->nullable();
            $table->date('change_date');
            $table->datetime('expires_at');
            $table->datetime('scheduled_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upcoming_changes');
    }
};
