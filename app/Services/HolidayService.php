<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class HolidayService
{
    /**
     * @throws RequestException
     */
    public function all(): array
    {
        $payload = Http::timeout(8)
            ->get(config('services.holidays.url'))
            ->throw()
            ->json();

        if (($payload['status'] ?? null) !== 'success' || ! isset($payload['data']) || ! is_array($payload['data'])) {
            return [
                'status' => 'error',
                'data' => [],
            ];
        }

        $payload['data'] = array_map(fn (array $holiday) => $this->transform($holiday), $payload['data']);

        return $payload;
    }

    private function transform(array $holiday): array
    {
        $holiday['description_en'] = $this->translateDescription($holiday['description'] ?? '');

        return $holiday;
    }

    private function translateDescription(string $description): string
    {
        if (Str::startsWith($description, 'Cuti Bersama')) {
            $base = trim(Str::after($description, 'Cuti Bersama'));

            return 'Joint Leave' . ($base !== '' ? ' — ' . $this->translateDescription($base) : '');
        }

        foreach (config('holidays.translations', []) as $source => $target) {
            if (Str::contains($description, $source)) {
                return $target;
            }
        }

        return $description;
    }
}
