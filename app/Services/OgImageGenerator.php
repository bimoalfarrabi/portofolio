<?php

namespace App\Services;

use App\Models\PortfolioProject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class OgImageGenerator
{
    private const DISK = 'public';
    private const CACHE_DIR = 'og-cache';
    private const WIDTH = 1200;
    private const HEIGHT = 630;

    /**
     * Return a public path (relative to the public disk) to the cached OG PNG
     * for the given project, generating it on demand. Returns null when the
     * image could not be rendered (caller should fall back to the static image).
     */
    public function forProject(PortfolioProject $project): ?string
    {
        $fingerprint = $this->fingerprint($project);
        $relativePath = self::CACHE_DIR.'/project-'.$project->getKey().'-'.$fingerprint.'.png';
        $disk = Storage::disk(self::DISK);

        if ($disk->exists($relativePath)) {
            return $relativePath;
        }

        $svg = View::make('og.project', [
            'project' => $project,
            'titleLines' => $this->wrap((string) $project->title, 18, 3),
            'descriptionLines' => $this->wrap((string) ($project->description ?: ''), 56, 2),
            'stack' => collect($project->stack ?? [])->take(5)->all(),
            'meta' => trim(implode('  ·  ', array_filter([$project->category, $project->year]))),
            'isClosed' => $project->type === 'closed',
        ])->render();

        $png = $this->renderSvgToPng($svg);

        if ($png === null) {
            return null;
        }

        $this->pruneOldVariants($project->getKey(), $relativePath);
        $disk->put($relativePath, $png);

        return $relativePath;
    }

    private function fingerprint(PortfolioProject $project): string
    {
        return substr(hash('sha256', implode('|', [
            $project->title,
            $project->category,
            $project->year,
            $project->type,
            $project->description,
            implode(',', $project->stack ?? []),
            optional($project->updated_at)->timestamp,
        ])), 0, 12);
    }

    private function renderSvgToPng(string $svg): ?string
    {
        $finder = new ExecutableFinder();
        $binary = $finder->find('rsvg-convert');

        $tmpSvg = tempnam(sys_get_temp_dir(), 'og_').'.svg';
        file_put_contents($tmpSvg, $svg);

        try {
            if ($binary !== null) {
                $process = new Process([
                    $binary,
                    '-w', (string) self::WIDTH,
                    '-h', (string) self::HEIGHT,
                    '-o', '/dev/stdout',
                    $tmpSvg,
                ]);
                $process->run();
                if ($process->isSuccessful()) {
                    return $process->getOutput();
                }
            }

            $magick = $finder->find('magick') ?? $finder->find('convert');
            if ($magick !== null) {
                $process = new Process([
                    $magick,
                    '-background', 'none',
                    '-density', '96',
                    $tmpSvg,
                    '-resize', self::WIDTH.'x'.self::HEIGHT,
                    'png:-',
                ]);
                $process->run();
                if ($process->isSuccessful()) {
                    return $process->getOutput();
                }
            }

            return null;
        } finally {
            @unlink($tmpSvg);
        }
    }

    private function pruneOldVariants(int|string $projectId, string $keepPath): void
    {
        $disk = Storage::disk(self::DISK);
        $prefix = self::CACHE_DIR.'/project-'.$projectId.'-';

        foreach ($disk->files(self::CACHE_DIR) as $file) {
            if (Str::startsWith($file, $prefix) && $file !== $keepPath) {
                $disk->delete($file);
            }
        }
    }

    /**
     * @return array<int, string>
     */
    private function wrap(string $text, int $perLine, int $maxLines): array
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        if ($text === '') {
            return [];
        }

        $words = explode(' ', $text);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current.' '.$word;
            if (mb_strlen($candidate) > $perLine && $current !== '') {
                $lines[] = $current;
                $current = $word;
                if (count($lines) === $maxLines) {
                    break;
                }
            } else {
                $current = $candidate;
            }
        }

        if (count($lines) < $maxLines && $current !== '') {
            $lines[] = $current;
        }

        if (count($lines) === $maxLines) {
            $last = array_pop($lines);
            if (mb_strlen($last) > $perLine) {
                $last = mb_substr($last, 0, $perLine - 1).'…';
            }
            $lines[] = $last;
        }

        return $lines;
    }
}
