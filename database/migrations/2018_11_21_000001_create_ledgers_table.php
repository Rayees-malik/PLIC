<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledgers', static function (Blueprint $table): void {
            $table->increments('id');
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->morphs('recordable');
            $table->unsignedTinyInteger('context');
            $table->string('event');
            $table->json('properties');
            $table->json('modified');
            $table->json('pivot');
            $table->json('extra');
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('signature');
            $table->timestamps();

            $table->index([
                'user_id',
                'user_type',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledgers');
    }
};
