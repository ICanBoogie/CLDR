<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

use ICanBoogie\PropertyNotDefined;
use PHPUnit\Framework\TestCase;

final class CalendarTest extends TestCase
{
	/**
	 * @var Calendar
	 */
	static private $calendar;

	static public function setupBeforeClass(): void
	{
		self::$calendar = get_repository()->locales['fr']->calendars['gregorian'];
	}

	public function test_instanceof(): void
	{
		$this->assertInstanceOf(Calendar::class, self::$calendar);
	}

	/**
	 * @dataProvider provider_test_property_instanceof
	 */
	public function test_property_instanceof(string $property, string $expected): void
	{
		$instance = self::$calendar->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, self::$calendar->$property);
	}

	public function provider_test_property_instanceof(): array
	{
		return [

			[ 'locale',             Locale::class ],
			[ 'datetime_formatter', DateTimeFormatter::class ],
			[ 'date_formatter',     DateFormatter::class ],
			[ 'time_formatter',     TimeFormatter::class ]

		];
	}

	public function test_get_undefined_property(): void
	{
		$this->expectException(PropertyNotDefined::class);
		self::$calendar->undefined_property;
	}

	/**
	 * @dataProvider provide_test_access
	 */
	public function test_access(string $key): void
	{
		$this->assertTrue(self::$calendar->offsetExists($key));
	}

	public function provide_test_access(): array
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

	/**
	 * @dataProvider provide_test_date_patterns_shortcuts
	 */
	public function test_date_patterns_shortcuts(string $property, string $path): void
	{
		$path_parts = explode('/', $path);
		$expected = self::$calendar;

		foreach ($path_parts as $part)
		{
			$expected = $expected[$part];
		}

		$this->assertEquals(self::$calendar->$property, $expected);
	}

	public function provide_test_date_patterns_shortcuts(): array
	{
		return [

			[ 'standalone_abbreviated_days',     'days/stand-alone/abbreviated' ],
			[ 'standalone_abbreviated_eras',     'eras/eraAbbr' ],
			[ 'standalone_abbreviated_months',   'months/stand-alone/abbreviated' ],
			[ 'standalone_abbreviated_quarters', 'quarters/stand-alone/abbreviated' ],
			[ 'standalone_narrow_days',          'days/stand-alone/narrow' ],
			[ 'standalone_narrow_eras',          'eras/eraNarrow' ],
			[ 'standalone_narrow_months',        'months/stand-alone/narrow' ],
			[ 'standalone_narrow_quarters',      'quarters/stand-alone/narrow' ],
			[ 'standalone_short_days',           'days/stand-alone/short' ],
			[ 'standalone_short_eras',           'eras/eraAbbr' ],
			[ 'standalone_short_months',         'months/stand-alone/abbreviated' ],
			[ 'standalone_short_quarters',       'quarters/stand-alone/abbreviated' ],
			[ 'standalone_wide_days',            'days/stand-alone/wide' ],
			[ 'standalone_wide_eras',            'eras/eraNames' ],
			[ 'standalone_wide_months',          'months/stand-alone/wide' ],
			[ 'standalone_wide_quarters',        'quarters/stand-alone/wide' ],
			[ 'abbreviated_days',                'days/format/abbreviated' ],
			[ 'abbreviated_eras',                'eras/eraAbbr' ],
			[ 'abbreviated_months',              'months/format/abbreviated' ],
			[ 'abbreviated_quarters',            'quarters/format/abbreviated' ],
			[ 'narrow_days',                     'days/format/narrow' ],
			[ 'narrow_eras',                     'eras/eraNarrow' ],
			[ 'narrow_months',                   'months/format/narrow' ],
			[ 'narrow_quarters',                 'quarters/format/narrow' ],
			[ 'short_days',                      'days/format/short' ],
			[ 'short_eras',                      'eras/eraAbbr' ],
			[ 'short_months',                    'months/format/abbreviated' ],
			[ 'short_quarters',                  'quarters/format/abbreviated' ],
			[ 'wide_days',                       'days/format/wide' ],
			[ 'wide_eras',                       'eras/eraNames' ],
			[ 'wide_months',                     'months/format/wide' ],
			[ 'wide_quarters',                   'quarters/format/wide' ]

		];
	}

	public function testFormatDateTime(): void
    {
        $this->assertSame(
            self::$calendar->format_datetime('2018-11-24 20:12:22 UTC', 'full'),
            "samedi 24 novembre 2018 à 20:12:22 UTC"
        );
    }

	public function testFormatDate(): void
    {
        $this->assertSame(
            self::$calendar->format_date('2018-11-24 20:12:22 UTC', 'long'),
            "24 novembre 2018"
        );
    }

	public function testFormatTime(): void
    {
        $this->assertSame(
            self::$calendar->format_time('2018-11-24 20:12:22 UTC', 'long'),
            "20:12:22 UTC"
        );
    }
}
