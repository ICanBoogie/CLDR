<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\Supplemental;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SupplementalTest extends TestCase
{
    private static Supplemental $sut;

    public static function setupBeforeClass(): void
    {
        self::$sut = get_repository()->supplemental;
    }

    #[DataProvider('provide_test_sections')]
    public function test_sections(string $section, string $key): void
    {
        $section_data = self::$sut[$section];
        $this->assertIsArray($section_data);
        $this->assertArrayHasKey($key, $section_data);
    }

    public static function provide_test_sections(): array
    {
        return [

            [ 'aliases', 'languageAlias' ],
            [ 'calendarData', 'buddhist' ],
            [ 'calendarPreferenceData', 'AE' ],
            [ 'characterFallbacks', 'U+00AD' ],
            [ 'codeMappings', 'AA' ],
            [ 'currencyData', 'fractions' ],
            [ 'dayPeriods', 'af' ],
            [ 'gender', 'personList' ],
            [ 'grammaticalFeatures', 'am-targets-nominal' ],
            [ 'languageData', 'aa' ],
            [ 'languageGroups', 'aav' ],
            [ 'languageMatching', 'written-new' ],
            [ 'likelySubtags', 'aa' ],
            [ 'measurementData', 'measurementSystem' ],
            [ 'metaZones', 'metazoneInfo' ],
            [ 'numberingSystems', 'armn' ],
            [ 'ordinals', 'af' ],
            [ 'parentLocales', 'en-150' ],
            [ 'pluralRanges', 'af' ],
            [ 'plurals', 'af' ],
            [ 'primaryZones', 'CL' ],
            [ 'references', 'R1000' ],
            [ 'territoryContainment', 'EU' ],
            [ 'territoryInfo', 'AC' ],
            [ 'timeData', 'AD' ],
            [ 'unitPreferenceData', 'area' ],
            [ 'weekData', 'minDays' ],
            [ 'windowsZones', 'mapTimezones' ],

        ];
    }

    public function test_default_calendar(): void
    {
        $this->assertArrayHasKey('001', self::$sut['calendarPreferenceData']);
    }

    public function test_offset_exists(): void
    {
        $s = self::$sut;

        $this->assertTrue(isset($s['calendarPreferenceData']));
        $this->assertTrue(isset($s['numberingSystems']));
        $this->assertFalse(isset($s[uniqid()]));
    }

    public function test_should_throw_exception_when_getting_undefined_offset(): void
    {
        $s = self::$sut;
        $this->expectException(LogicException::class);
        $s[uniqid()]; // @phpstan-ignore-line
    }

    public function test_should_throw_exception_in_attempt_to_set_offset(): void
    {
        $s = self::$sut;
        $this->expectException(LogicException::class);
        $s['timeData'] = null;
    }

    public function test_should_throw_exception_in_attempt_to_unset_offset(): void
    {
        $s = self::$sut;
        $this->expectException(LogicException::class);
        unset($s['timeData']);
    }

    public function test_warm_up(): void
    {
        $n = 0;

        self::$sut->warm_up(function () use (&$n) {
            $n++;
        });

        $this->assertEquals(29, $n);
    }
}
