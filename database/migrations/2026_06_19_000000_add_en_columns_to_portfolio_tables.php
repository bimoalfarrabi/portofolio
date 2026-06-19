<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
            $table->text('approach_en')->nullable()->after('approach');
            $table->text('outcome_en')->nullable()->after('outcome');
        });

        Schema::table('portfolio_logs', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('body_en')->nullable()->after('body');
        });

        Schema::table('portfolio_stats', function (Blueprint $table) {
            $table->string('label_en')->nullable()->after('label');
            $table->string('note_en')->nullable()->after('note');
        });

        Schema::table('portfolio_collab', function (Blueprint $table) {
            $table->string('available_label_en')->nullable()->after('available_label');
            $table->string('busy_label_en')->nullable()->after('busy_label');
            $table->string('location_en')->nullable()->after('location');
            $table->string('time_zone_label_en')->nullable()->after('time_zone_label');
            $table->string('response_time_en')->nullable()->after('response_time');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en', 'approach_en', 'outcome_en']);
        });

        Schema::table('portfolio_logs', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'body_en']);
        });

        Schema::table('portfolio_stats', function (Blueprint $table) {
            $table->dropColumn(['label_en', 'note_en']);
        });

        Schema::table('portfolio_collab', function (Blueprint $table) {
            $table->dropColumn(['available_label_en', 'busy_label_en', 'location_en', 'time_zone_label_en', 'response_time_en']);
        });
    }
};
