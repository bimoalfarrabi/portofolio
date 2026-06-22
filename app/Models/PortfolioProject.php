<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\HasTranslation;

class PortfolioProject extends Model
{
    use HasTranslation;
    protected $table = 'portfolio_projects';

    protected $fillable = [
        'title',
        'title_en',
        'type',
        'repo_urls',
        'web_url',
        'category',
        'year',
        'image',
        'gallery',
        'description',
        'description_en',
        'approach',
        'approach_en',
        'stack',
        'outcome',
        'outcome_en',
        'x_position',
        'y_position',
        'size',
        'accent',
        'label_placement',
        'sort_order',
        'is_published',
        'is_featured',
    ];

    protected $appends = ['image_url', 'gallery_urls'];

    protected function casts(): array
    {
        return [
            'stack' => 'array',
            'gallery' => 'array',
            'repo_urls' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            return $this->resolveImageUrl($this->image);
        });
    }

    protected function galleryUrls(): Attribute
    {
        return Attribute::get(function (): array {
            $items = is_array($this->gallery) ? $this->gallery : [];

            return collect($items)
                ->map(fn ($value) => $this->resolveImageUrl($value))
                ->filter()
                ->values()
                ->all();
        });
    }

    private function resolveImageUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://', '//', 'data:'])) {
            return $value;
        }

        $path = ltrim($value, '/');

        if (Str::startsWith($path, 'storage/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}
