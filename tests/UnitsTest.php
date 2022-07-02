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

use BadMethodCallException;
use PHPUnit\Framework\TestCase;

final class UnitsTest extends TestCase
{
    use StringHelpers;

	/**
	 * @var LocaleCollection
	 */
	static private $locales;

	static public function setUpBeforeClass(): void
	{
		self::$locales = get_repository()->locales;
	}

	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param float|int $number
	 */
	public function test_cases(string $locale, string $unit, $number, string $length, string $expected): void
	{
		$this->assertSame($expected, $this->units_for($locale)->$unit($number)->{ 'as_' . $length });
	}

	public function provide_test_cases(): array
	{
		return [

			[ 'fr', 'acceleration-g-force', 123.4504, Units::LENGTH_LONG, "123,45 fois l’accélération de pesanteur terrestre" ],
			[ 'fr', 'digital_gigabyte', 123.4504, Units::LENGTH_LONG, "123,45 gigaoctets" ],
			[ 'fr', 'digital_gigabyte', 123.4504, Units::LENGTH_SHORT, "123,45 Go" ],
			[ 'fr', 'digital_gigabyte', 123.4504, Units::LENGTH_NARROW, "123,45Go" ],
			[ 'fr', 'duration_hour', 123.4504, Units::LENGTH_LONG, "123,45 heures" ],
			[ 'fr', 'duration_hour', 123.4504, Units::LENGTH_SHORT, "123,45 h" ],
			[ 'fr', 'duration_hour', 123.4504, Units::LENGTH_NARROW, "123,45h" ],

		];
	}

	/**
	 * @dataProvider provide_test_format_combination
	 *
	 * @param float|int $number
	 */
	public function test_format_combination(
		string $locale,
		$number,
		string $number_unit,
		string $per_unit,
		string $length,
		string $expected
	): void
	{
		$this->assertSame(
			$expected,
			$this->units_for($locale)->format_combination($number, $number_unit, $per_unit, $length)
		);
	}

	public function provide_test_format_combination(): array
	{
		return [

			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_LONG, "12.345 liters per hour" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_SHORT, "12.345 L/h" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_NARROW, "12.345L/h" ],

			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_LONG, "12,345 litres par heure" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_SHORT, "12,345 l/h" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', Units::LENGTH_NARROW, "12,345l/h" ],

			[ 'fr', 12.345, 'volume-liter', 'area-square-meter', Units::LENGTH_LONG, "12,345 litres par mètre carré"],
			[ 'fr', 12.345, 'angle-revolution', 'length-light-year', Units::LENGTH_LONG, "12,345 tours par années-lumière"],

		];
	}

	/**
	 * @dataProvider provide_test_format_sequence
	 */
	public function test_format_sequence(string $locale, callable $sequence, string $expected): void
	{
		$this->assertStringSame($expected, $sequence($this->units_for($locale)));
	}

	public function provide_test_format_sequence(): array
	{
	    $s1 = Spaces::NARROW_NO_BREAK_SPACE;
	    $s2 = Spaces::NO_BREAK_SPACE;

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

			}, "12 hr, 34 min, 56 sec"  ],

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

				}, "12{$s2}heures, 34 minutes et 56{$s2}secondes"  ],

			[ 'fr', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_short;

				}, "12{$s1}h, 34{$s2}min et 56{$s1}s" ],

			[ 'fr', function (Units $units) {

				return $units->sequence
					->duration_hour(12)
					->duration_minute(34)
					->duration_second(56)
					->as_narrow;

				}, "12h 34min 56s" ],

		];
	}

	/**
	 * @dataProvider provide_name_for
	 */
	public function test_name_for(string $unit, string $length, string $expected_name): void
	{
		$this->assertSame($expected_name, $this->units_for('fr')->name_for($unit, $length));
	}

	public function provide_name_for(): array
	{
		return [

			[ 'angle_degree', Units::LENGTH_LONG, "degrés" ],
			[ 'angle_degree', Units::LENGTH_SHORT, "°" ],
			[ 'angle_degree', Units::LENGTH_NARROW, "°" ],
			[ 'digital-megabyte', Units::LENGTH_LONG, "mégaoctets" ],
			[ 'digital-megabyte', Units::LENGTH_SHORT, "Mo" ],
			[ 'digital-megabyte', Units::LENGTH_NARROW, "Mo" ],

		];
	}

	public function test_getter(): void
	{
		$units = $this->units_for('fr');
		$unit = $units->angle_degree;

		$this->assertSame($unit, $units->angle_degree);
	}

	/**
	 * @test
	 */
	public function should_fail_with_undefined_unit()
	{
		$this->expectExceptionMessage("No such unit: undefined-unit");
		$this->expectException(BadMethodCallException::class);
		$this->units_for('fr')->{ 'undefined_unit' }();
	}

	public function test_unit_method_requires_one_argument(): void
	{
		$this->expectExceptionMessage("acceleration_g_force() expects one argument, got 0");
		$this->expectException(BadMethodCallException::class);

		$this->units_for('en')->acceleration_g_force(); // @phpstan-ignore-line
	}

	private function units_for($locale): Units
	{
		return new Units(self::$locales[$locale]);
	}
}
