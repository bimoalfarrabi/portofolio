<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->json('repo_urls')->nullable()->after('type');
        });

        // Migrate existing repo_url data into repo_urls JSON
        DB::table('portfolio_projects')
            ->whereNotNull('repo_url')
            ->where('repo_url', '!=', '')
            ->eachById(function ($project) {
                DB::table('portfolio_projects')
                    ->where('id', $project->id)
                    ->update([
                        'repo_urls' => json_encode([
                            ['label' => 'Repository', 'url' => $project->repo_url],
                        ]),
                    ]);
            });

        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropColumn('repo_url');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->string('repo_url')->nullable()->after('type');
        });

        // Restore first repo_url from JSON
        DB::table('portfolio_projects')
            ->whereNotNull('repo_urls')
            ->eachById(function ($project) {
                $urls = json_decode($project->repo_urls, true);
                if (! empty($urls[0]['url'])) {
                    DB::table('portfolio_projects')
                        ->where('id', $project->id)
                        ->update(['repo_url' => $urls[0]['url']]);
                }
            });

        Schema::table('portfolio_projects', function (Blueprint $table) {
            $table->dropColumn('repo_urls');
        });
    }
};
