<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class HolidayControllerTest extends TestCase
{
    public function test_it_returns_holiday_data_with_english_translation(): void
    {
        Http::fake([
            config('services.holidays.url') => Http::response([
                'status' => 'success',
                'count' => 1,
                'data' => [
                    [
                        'holiday_date' => '2026-05-31',
                        'description' => 'Hari Raya Waisak 2570 BE',
                        'holiday_type' => 'libur_nasional',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/holidays');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                [
                    'holiday_date' => '2026-05-31',
                    'description' => 'Hari Raya Waisak 2570 BE',
                    'description_en' => 'Vesak Day',
                ],
            ],
        ]);
    }

    public function test_it_returns_502_when_remote_api_fails(): void
    {
        Http::fake([
            config('services.holidays.url') => Http::response(null, 500),
        ]);

        $response = $this->getJson('/api/holidays');

        $response->assertStatus(502);
        $response->assertJson([
            'status' => 'error',
            'data' => [],
        ]);
    }

    /** @test */
    public function test_it_handles_empty_holiday_data(): void
    {
        Http::fake([
            config('services.holidays.url') => Http::response([
                'status' => 'success',
                'count' => 0,
                'data' => [],
            ], 200),
        ]);

        $response = $this->getJson('/api/holidays');

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [],
        ]);
    }
}
