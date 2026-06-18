<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $tags = collect(explode(',', (string) $this->input('tags', '')))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();

        $this->merge([
            'tags' => $tags,
            'is_published' => $this->boolean('is_published'),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'logged_at' => ['nullable', 'date'],
            'body' => ['nullable', 'string'],
            'tags' => ['array'],
            'tags.*' => ['string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_published' => ['boolean'],
        ];
    }
}
