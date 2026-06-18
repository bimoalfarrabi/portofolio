<?php

namespace Tests\Unit;

use App\Services\HolidayService;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class HolidayServiceTest extends TestCase
{
    #[DataProvider('translationProvider')]
    public function test_translate_description(
        string $indonesian,
        string $expectedEnglish,
    ): void {
        $service = new HolidayService;
        $reflection = new \ReflectionMethod($service, 'translateDescription');
        $reflection->setAccessible(true);

        $result = $reflection->invoke($service, $indonesian);

        $this->assertSame($expectedEnglish, $result);
    }

    public static function translationProvider(): array
    {
        return [
            'Vesak Day' => ['Hari Raya Waisak 2570 BE', 'Vesak Day'],
            'Eid al-Fitr' => ['Hari Raya Idul Fitri 1447 Hijriyah', 'Eid al-Fitr'],
            'Chinese New Year' => ['Tahun Baru Imlek 2577 Kongzili', 'Chinese New Year'],
            'Good Friday' => ['Wafat Yesus Kristus / Jumat Agung', 'Good Friday'],
            'Independence Day' => ['Hari Kemerdekaan Republik Indonesia', 'Indonesian Independence Day'],
            'Joint Leave' => ['Cuti Bersama Hari Raya Idul Adha 1447 Hijriyah', 'Joint Leave — Eid al-Adha'],
            'Unknown holiday fallback' => ['Hari Libur Tidak Dikenal', 'Hari Libur Tidak Dikenal'],
        ];
    }
}
