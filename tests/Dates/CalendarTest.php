<?php

namespace Test\ICanBoogie\CLDR\Dates;

use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Dates\Calendar;
use ICanBoogie\CLDR\Dates\DateFormatter;
use ICanBoogie\CLDR\Dates\DateTimeFormatLength;
use ICanBoogie\CLDR\Dates\DateTimeFormatter;
use ICanBoogie\CLDR\Dates\TimeFormatter;
use ICanBoogie\PropertyNotDefined;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\locale_for;

final class CalendarTest extends TestCase
{
    private static Calendar $sut;

    public static function setupBeforeClass(): void
    {
        self::$sut = locale_for('fr')->calendars['gregorian'];
    }

    #[DataProvider('provider_test_property_instanceof')]
    public function test_property_instanceof(string $property, string $expected): void
    {
        $instance = self::$sut->$property;
        $this->assertInstanceOf($expected, $instance); // @phpstan-ignore-line
        $this->assertSame($instance, self::$sut->$property);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provider_test_property_instanceof(): array
    {
        return [

            [ 'locale', Locale::class ],
            [ 'datetime_formatter', DateTimeFormatter::class ],
            [ 'date_formatter', DateFormatter::class ],
            [ 'time_formatter', TimeFormatter::class ]

        ];
    }

    public function test_get_undefined_property(): void
    {
        $this->expectException(PropertyNotDefined::class);
        self::$sut->undefined_property; // @phpstan-ignore-line
    }

    #[DataProvider('provide_test_access')]
    public function test_access(string $key): void
    {
        $this->assertTrue(self::$sut->offsetExists($key));
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_access(): array
    {
        return [

            [ 'months' ],
            [ 'days' ],
            [ 'quarters' ],
            [ 'dayPeriods' ],
            [ 'eras' ],
            [ 'dateFormats' ],
            [ 'timeFormats' ],
            [ 'dateTimeFormats' ]

        ];
    }

    #[DataProvider('provide_test_date_patterns_shortcuts')]
    public function test_date_patterns_shortcuts(string $property, string $path): void
    {
        $path_parts = explode('/', $path);
        $expected = self::$sut;

        foreach ($path_parts as $part) {
            $expected = $expected[$part];
        }

        $this->assertEquals(self::$sut->$property, $expected);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_date_patterns_shortcuts(): array
    {
        return [

            [ 'standalone_abbreviated_days', 'days/stand-alone/abbreviated' ],
            [ 'standalone_abbreviated_eras', 'eras/eraAbbr' ],
            [ 'standalone_abbreviated_months', 'months/stand-alone/abbreviated' ],
            [ 'standalone_abbreviated_quarters', 'quarters/stand-alone/abbreviated' ],
            [ 'standalone_narrow_days', 'days/stand-alone/narrow' ],
            [ 'standalone_narrow_eras', 'eras/eraNarrow' ],
            [ 'standalone_narrow_months', 'months/stand-alone/narrow' ],
            [ 'standalone_narrow_quarters', 'quarters/stand-alone/narrow' ],
            [ 'standalone_short_days', 'days/stand-alone/short' ],
            [ 'standalone_short_eras', 'eras/eraAbbr' ],
            [ 'standalone_short_months', 'months/stand-alone/abbreviated' ],
            [ 'standalone_short_quarters', 'quarters/stand-alone/abbreviated' ],
            [ 'standalone_wide_days', 'days/stand-alone/wide' ],
            [ 'standalone_wide_eras', 'eras/eraNames' ],
            [ 'standalone_wide_months', 'months/stand-alone/wide' ],
            [ 'standalone_wide_quarters', 'quarters/stand-alone/wide' ],
            [ 'abbreviated_days', 'days/format/abbreviated' ],
            [ 'abbreviated_eras', 'eras/eraAbbr' ],
            [ 'abbreviated_months', 'months/format/abbreviated' ],
            [ 'abbreviated_quarters', 'quarters/format/abbreviated' ],
            [ 'narrow_days', 'days/format/narrow' ],
            [ 'narrow_eras', 'eras/eraNarrow' ],
            [ 'narrow_months', 'months/format/narrow' ],
            [ 'narrow_quarters', 'quarters/format/narrow' ],
            [ 'short_days', 'days/format/short' ],
            [ 'short_eras', 'eras/eraAbbr' ],
            [ 'short_months', 'months/format/abbreviated' ],
            [ 'short_quarters', 'quarters/format/abbreviated' ],
            [ 'wide_days', 'days/format/wide' ],
            [ 'wide_eras', 'eras/eraNames' ],
            [ 'wide_months', 'months/format/wide' ],
            [ 'wide_quarters', 'quarters/format/wide' ]

        ];
    }

    public function testFormatDateTime(): void
    {
        $actual = self::$sut->format_datetime('2018-11-24 20:12:22 UTC', DateTimeFormatLength::FULL);

        $this->assertSame("samedi 24 novembre 2018 à 20:12:22 UTC", $actual);
    }

    public function testFormatDate(): void
    {
        $actual = self::$sut->format_date('2018-11-24 20:12:22 UTC', DateTimeFormatLength::LONG);

        $this->assertSame("24 novembre 2018", $actual);
    }

    public function testFormatTime(): void
    {
        $actual = self::$sut->format_time('2018-11-24 20:12:22 UTC', DateTimeFormatLength::LONG);

        $this->assertSame("20:12:22 UTC", $actual);
    }
}
