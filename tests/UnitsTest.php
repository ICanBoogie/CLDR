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

class UnitsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var LocaleCollection
	 */
	private $locales;

	public function setUp()
	{
		$locales = &$this->locales;

		if (!$locales)
		{
			$locales = get_repository()->locales;
		}
	}

	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param string $locale
	 * @param string $unit
	 * @param number $number
	 * @param string $length
	 * @param string $expected
	 */
	public function test_cases($locale, $unit, $number, $length, $expected)
	{
		$this->assertSame($expected, $this->units_for($locale)->$unit($number, $length));
	}

	public function provide_test_cases()
	{
		return [

			[ 'fr', 'acceleration-g-force', 123.4504, Units::LENGTH_LONG, "123,45 fois la gravitation terrestre" ]

		];
	}

	/**
	 * @dataProvider provide_test_format_combination
	 *
	 * @param string $locale
	 * @param number $number
	 * @param string $number_unit
	 * @param string $per_unit
	 * @param string $length
	 * @param string $expected
	 */
	public function test_format_combination($locale, $number, $number_unit, $per_unit, $length, $expected)
	{
		$this->assertSame(
			$expected,
			$this->units_for($locale)->format_combination($number, $number_unit, $per_unit, $length)
		);
	}

	public function provide_test_format_combination()
	{
		return [

			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_LONG, "12.345 liters per hour" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_SHORT, "12.345 Lph" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_NARROW, "12.345l/h" ],

			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_LONG, "12,345 litres par heure" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_SHORT, "12,345 L/h" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_NARROW, "12,345L/h" ],

			[ 'fr', 12.345, 'volume-liter', 'area-square-meter', Units::LENGTH_LONG, "12,345 litres par mètres carrés"]

		];
	}

	/**
	 * @dataProvider provide_test_format_sequence
	 *
	 * @param string $locale
	 * @param callable $sequence
	 * @param string $expected
	 */
	public function test_format_sequence($locale, callable $sequence, $expected)
	{
		$this->assertSame($expected, $sequence($this->units_for($locale)));
	}

	public function provide_test_format_sequence()
	{
		return [

			[ 'en', function (Units $units) {

				return $units->sequence
					->angle_degree(5)
					->duration_minute(30)
					->as_long;

			}, "5 degrees, 30 minutes" ],

			[ 'en', function (Units $units) {

				return $units->sequence
					->angle_degree(5)
					->duration_minute(30)
					->as_narrow;

			}, "5° 30m" ],

			[ 'en', function (Units $units) {

				return $units->sequence
					->length_foot(3)
					->length_inch(2)
					->as_short;

			}, "3 ft, 2 in" ],

			[ 'en', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_long;

			}, "12 hours, 34 minutes, 56 seconds"  ],

			[ 'en', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_short;

			}, "12 hrs, 34 mins, 56 secs"  ],

			[ 'en', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_narrow;

			}, "12h 34m 56s"  ],

			[ 'fr', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_long;

				}, "12 heures, 34 minutes et 56 secondes"  ],

			[ 'fr', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_short;

				}, "12 h, 34 min et 56 s" ],

			[ 'fr', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_narrow;

				}, "12h 34m 56s" ],

		];
	}

	private function units_for($locale)
	{
		return new Units($this->locales[$locale]);
	}
}
