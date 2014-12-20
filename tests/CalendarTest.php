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

class CalendarTest extends \PHPUnit_Framework_TestCase
{
	static private $calendar;

	static public function setupBeforeClass()
	{
		$repository = new Repository(create_provider_stack());
		self::$calendar = $repository->locales['fr']->calendars['gregorian'];
	}

	public function test_instanceof()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Calendar', self::$calendar);
	}

	/**
	 * @dataProvider provider_test_property_instanceof
	 */
	public function test_property_instanceof($property, $expected)
	{
		$instance = self::$calendar->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, self::$calendar->$property);
	}

	public function provider_test_property_instanceof()
	{
		return [

			[ 'locale',             'ICanBoogie\CLDR\Locale' ],
			[ 'datetime_formatter', 'ICanBoogie\CLDR\DateTimeFormatter' ],
			[ 'date_formatter',     'ICanBoogie\CLDR\DateFormatter' ],
			[ 'time_formatter',     'ICanBoogie\CLDR\TimeFormatter' ]

		];
	}

	/**
	 * @expectedException \ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined_property()
	{
		self::$calendar->undefined_property;
	}

	/**
	 * @dataProvider provide_test_access
	 */
	public function test_access($key)
	{
		$this->assertTrue(self::$calendar->offsetExists($key));
	}

	public function provide_test_access()
	{
		return array
		(
			array('months'),
			array('days'),
			array('quarters'),
			array('dayPeriods'),
			array('eras'),
			array('dateFormats'),
			array('timeFormats'),
			array('dateTimeFormats')
		);
	}

	/**
	 * @dataProvider provide_test_date_patterns_shortcuts
	 */
	public function test_date_patterns_shortcuts($property, $path)
	{
		$path_parts = explode('/', $path);
		$expected = self::$calendar;

		foreach ($path_parts as $part)
		{
			$expected = $expected[$part];
		}

		$this->assertEquals(self::$calendar->$property, $expected);
	}

	public function provide_test_date_patterns_shortcuts()
	{
		return array
		(
			array('standalone_abbreviated_days',     'days/stand-alone/abbreviated'),
			array('standalone_abbreviated_eras',     'eras/eraAbbr'),
			array('standalone_abbreviated_months',   'months/stand-alone/abbreviated'),
			array('standalone_abbreviated_quarters', 'quarters/stand-alone/abbreviated'),
			array('standalone_narrow_days',          'days/stand-alone/narrow'),
			array('standalone_narrow_eras',          'eras/eraNarrow'),
			array('standalone_narrow_months',        'months/stand-alone/narrow'),
			array('standalone_narrow_quarters',      'quarters/stand-alone/narrow'),
			array('standalone_short_days',           'days/stand-alone/short'),
			array('standalone_short_eras',           'eras/eraAbbr'),
			array('standalone_short_months',         'months/stand-alone/abbreviated'),
			array('standalone_short_quarters',       'quarters/stand-alone/abbreviated'),
			array('standalone_wide_days',            'days/stand-alone/wide'),
			array('standalone_wide_eras',            'eras/eraNames'),
			array('standalone_wide_months',          'months/stand-alone/wide'),
			array('standalone_wide_quarters',        'quarters/stand-alone/wide'),
			array('abbreviated_days',                'days/format/abbreviated'),
			array('abbreviated_eras',                'eras/eraAbbr'),
			array('abbreviated_months',              'months/format/abbreviated'),
			array('abbreviated_quarters',            'quarters/format/abbreviated'),
			array('narrow_days',                     'days/format/narrow'),
			array('narrow_eras',                     'eras/eraNarrow'),
			array('narrow_months',                   'months/format/narrow'),
			array('narrow_quarters',                 'quarters/format/narrow'),
			array('short_days',                      'days/format/short'),
			array('short_eras',                      'eras/eraAbbr'),
			array('short_months',                    'months/format/abbreviated'),
			array('short_quarters',                  'quarters/format/abbreviated'),
			array('wide_days',                       'days/format/wide'),
			array('wide_eras',                       'eras/eraNames'),
			array('wide_months',                     'months/format/wide'),
			array('wide_quarters',                   'quarters/format/wide')
		);
	}
}
