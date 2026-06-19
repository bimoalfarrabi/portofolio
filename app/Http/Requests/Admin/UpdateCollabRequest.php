<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $channels = collect($this->input('channels', []))
            ->filter(fn ($channel) => is_array($channel) && filled($channel['label'] ?? null) && filled($channel['href'] ?? null))
            ->map(fn ($channel) => [
                'label' => trim((string) ($channel['label'] ?? '')),
                'href' => trim((string) ($channel['href'] ?? '')),
                'handle' => trim((string) ($channel['handle'] ?? '')),
            ])
            ->values()
            ->all();

        $this->merge([
            'available' => $this->boolean('available'),
            'channels' => $channels,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'available' => ['boolean'],
            'available_label' => ['required', 'string', 'max:255'],
            'available_label_en' => ['nullable', 'string', 'max:255'],
            'busy_label' => ['required', 'string', 'max:255'],
            'busy_label_en' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_en' => ['nullable', 'string', 'max:255'],
            'time_zone' => ['required', 'string', 'max:64', 'timezone'],
            'time_zone_label' => ['nullable', 'string', 'max:32'],
            'time_zone_label_en' => ['nullable', 'string', 'max:32'],
            'response_time' => ['nullable', 'string', 'max:255'],
            'response_time_en' => ['nullable', 'string', 'max:255'],
            'channels' => ['array'],
            'channels.*.label' => ['required', 'string', 'max:64'],
            'channels.*.href' => ['required', 'string', 'max:255'],
            'channels.*.handle' => ['nullable', 'string', 'max:120'],
        ];
    }
}
