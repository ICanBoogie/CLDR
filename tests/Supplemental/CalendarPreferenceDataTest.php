<?php

namespace Test\ICanBoogie\CLDR\Supplemental;

use ICanBoogie\CLDR\Supplemental\CalendarPreferenceData;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CalendarPreferenceDataTest extends TestCase
{
    #[DataProvider('provide_preferred_calendar_for_region')]
    public function test_preferred_calendar_for_region(string $region, string $expected): void
    {
        $actual = CalendarPreferenceData::preferred_calendar_for_region($region);

        $this->assertSame($expected, $actual);
    }

    public static function provide_preferred_calendar_for_region(): array
    {
        return [

            [ 'AE', 'gregorian' ],
            [ 'AF', 'persian' ],
            [ 'TH', 'buddhist' ],
            [ 'ZZ', 'gregorian' ],
            [ 'und', 'gregorian' ],

        ];
    }
}
