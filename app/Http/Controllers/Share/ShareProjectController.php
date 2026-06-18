<?php

namespace App\Http\Controllers\Share;

use App\Http\Controllers\Controller;
use App\Models\PortfolioLog;
use App\Models\PortfolioProject;
use App\Models\PortfolioSkill;
use App\Models\PortfolioStat;
use App\Services\OgImageGenerator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShareProjectController extends Controller
{
    /**
     * Public share landing for a single project: serves the SPA with
     * project-specific meta tags and instructs the frontend to auto-open
     * the matching project modal.
     */
    public function show(PortfolioProject $project): View
    {
        abort_unless($project->is_published, 404);

        $description = Str::of((string) ($project->description ?: 'Lihat detail project ini di orbit viasco prjkt.'))
            ->squish()
            ->limit(180)
            ->value();

        $title = trim($project->title.' — '.config('app.name', 'viasco prjkt.'));

        return view('welcome', [
            'portfolioData' => [
                'projects' => PortfolioProject::query()->where('is_published', true)->orderBy('sort_order')->get(),
                'skills' => PortfolioSkill::query()->where('is_active', true)->orderBy('sort_order')->get(),
                'logs' => PortfolioLog::query()->where('is_published', true)->orderBy('sort_order')->get(),
                'stats' => PortfolioStat::query()->where('is_active', true)->orderBy('sort_order')->get(),
                'focusProjectId' => $project->getKey(),
            ],
            'metaTitle' => $title,
            'metaDescription' => $description,
            'metaImage' => route('share.project.image', $project),
            'metaImageIsAbsolute' => true,
        ]);
    }

    /**
     * Serves the generated (and cached) OG image for a project.
     */
    public function image(PortfolioProject $project, OgImageGenerator $generator): Response
    {
        abort_unless($project->is_published, 404);

        $path = $generator->forProject($project);

        if ($path === null) {
            return redirect()->to(asset('og-image.png'));
        }

        $disk = Storage::disk('public');

        return response($disk->get($path), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
