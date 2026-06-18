<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'icon' => filled($this->input('icon')) ? $this->input('icon') : null,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $iconKeys = collect(config('skill_icons.groups', []))->flatten()->all();

        return [
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'in:' . implode(',', $iconKeys)],
            'category' => ['nullable', 'string', 'max:255'],
            'x_position' => ['nullable', 'string', 'max:20'],
            'y_position' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}
