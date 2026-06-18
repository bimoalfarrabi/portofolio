<?php

namespace App\Http\Controllers\Admin\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Models\PortfolioProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    private const IMAGE_DISK = 'public';
    private const IMAGE_DIR = 'projects';

    public function index(): View
    {
        $projects = PortfolioProject::query()->orderBy('sort_order')->latest('id')->paginate(15);

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new PortfolioProject(),
            'mode' => 'create',
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->resolveImage($request, null);
        $data['gallery'] = $this->resolveGallery($request, null);
        unset($data['image_file'], $data['remove_image'], $data['gallery_files'], $data['remove_gallery']);

        // Pastikan hanya satu featured per type
        if (! empty($data['is_featured'])) {
            PortfolioProject::where('type', $data['type'])->update(['is_featured' => false]);
        }

        PortfolioProject::create($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project tersimpan.');
    }

    public function edit(PortfolioProject $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'mode' => 'edit',
        ]);
    }

    public function update(StoreProjectRequest $request, PortfolioProject $project): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $this->resolveImage($request, $project);
        $data['gallery'] = $this->resolveGallery($request, $project);
        unset($data['image_file'], $data['remove_image'], $data['gallery_files'], $data['remove_gallery']);

        // Pastikan hanya satu featured per type
        if (! empty($data['is_featured'])) {
            PortfolioProject::where('type', $data['type'])
                ->where('id', '!=', $project->id)
                ->update(['is_featured' => false]);
        }

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('status', 'Project diperbarui.');
    }

    public function destroy(PortfolioProject $project): RedirectResponse
    {
        $this->deleteStoredImage($project->image);
        foreach ((array) $project->gallery as $galleryItem) {
            $this->deleteStoredImage($galleryItem);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project dihapus.');
    }

    /**
     * @return array<int, string>
     */
    private function resolveGallery(StoreProjectRequest $request, ?PortfolioProject $project): array
    {
        $existing = collect($project?->gallery ?? [])->filter()->values()->all();

        $remove = collect($request->input('remove_gallery', []))->filter()->values()->all();
        if (! empty($remove)) {
            foreach ($existing as $item) {
                if (in_array($item, $remove, true)) {
                    $this->deleteStoredImage($item);
                }
            }
            $existing = array_values(array_filter($existing, fn ($item) => ! in_array($item, $remove, true)));
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file && $file->isValid()) {
                    $existing[] = $file->store(self::IMAGE_DIR, self::IMAGE_DISK);
                }
            }
        }

        return array_values($existing);
    }

    private function resolveImage(StoreProjectRequest $request, ?PortfolioProject $project): ?string
    {
        $current = $project?->image;

        if ($request->hasFile('image_file')) {
            $stored = $request->file('image_file')->store(self::IMAGE_DIR, self::IMAGE_DISK);
            $this->deleteStoredImage($current);

            return $stored;
        }

        if ($request->boolean('remove_image')) {
            $this->deleteStoredImage($current);

            return null;
        }

        $url = trim((string) $request->input('image', ''));

        if ($url === '') {
            return $current;
        }

        if ($url !== $current) {
            $this->deleteStoredImage($current);
        }

        return $url;
    }

    private function deleteStoredImage(?string $value): void
    {
        if (! $value) {
            return;
        }

        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:'])) {
            return;
        }

        $path = ltrim($value, '/');
        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        if ($path === '' || ! Storage::disk(self::IMAGE_DISK)->exists($path)) {
            return;
        }

        Storage::disk(self::IMAGE_DISK)->delete($path);
    }
}
