<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_collab', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->boolean('available')->default(true);
            $table->string('available_label')->default('Available for new projects');
            $table->string('busy_label')->default('Booked, but still reading messages');
            $table->string('location')->nullable();
            $table->string('time_zone')->default('Asia/Jakarta');
            $table->string('time_zone_label')->nullable();
            $table->string('response_time')->nullable();
            $table->json('channels')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_collab');
    }
};
