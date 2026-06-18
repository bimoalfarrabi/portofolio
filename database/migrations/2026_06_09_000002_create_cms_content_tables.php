<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('logged_at')->nullable();
            $table->text('body')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('portfolio_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('main');
            $table->string('x_position')->nullable();
            $table->string('y_position')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('portfolio_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type')->default('open');
            $table->string('category')->nullable();
            $table->string('year')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->text('approach')->nullable();
            $table->json('stack')->nullable();
            $table->text('outcome')->nullable();
            $table->string('x_position')->nullable();
            $table->string('y_position')->nullable();
            $table->string('size')->nullable();
            $table->string('accent')->nullable();
            $table->string('label_placement')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('portfolio_stats', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('value');
            $table->string('note')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_stats');
        Schema::dropIfExists('portfolio_projects');
        Schema::dropIfExists('portfolio_skills');
        Schema::dropIfExists('portfolio_logs');
    }
};
