<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $stack = collect(explode(',', (string) $this->input('stack', '')))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();

        $removeGallery = $this->input('remove_gallery', []);
        if (! is_array($removeGallery)) {
            $removeGallery = [];
        }

        $this->merge([
            'stack' => $stack,
            'is_published' => $this->boolean('is_published'),
            'is_featured' => $this->boolean('is_featured'),
            'remove_image' => $this->boolean('remove_image'),
            'remove_gallery' => array_values(array_filter($removeGallery, fn ($v) => $v !== null && $v !== '')),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['open', 'closed'])],
            'category' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'string', 'max:2048'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'remove_image' => ['boolean'],
            'gallery_files' => ['nullable', 'array', 'max:12'],
            'gallery_files.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'remove_gallery' => ['array'],
            'remove_gallery.*' => ['string', 'max:2048'],
            'description' => ['nullable', 'string'],
            'approach' => ['nullable', 'string'],
            'stack' => ['array'],
            'stack.*' => ['string', 'max:50'],
            'outcome' => ['nullable', 'string'],
            'x_position' => ['nullable', 'string', 'max:20'],
            'y_position' => ['nullable', 'string', 'max:20'],
            'size' => ['nullable', 'string', 'max:50'],
            'accent' => ['nullable', 'string', 'max:50'],
            'label_placement' => ['nullable', Rule::in(['top', 'bottom'])],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }
}
