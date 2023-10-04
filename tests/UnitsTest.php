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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnitsTest extends TestCase
{
    use StringHelpers;

	static private LocaleCollection $locales;

	static public function setUpBeforeClass(): void
	{
		self::$locales = get_repository()->locales;
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	#[DataProvider('provide_test_cases')]
	public function test_cases(
		string $locale,
		string $unit,
		float|int|string $number,
		UnitLength $length,
		string $expected
	): void {
		$actual = $this->units_for($locale)->$unit($number)->{ 'as_' . $length->value };

		$this->assertSame($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_cases(): array
	{
		return [

			[ 'fr', 'acceleration-g-force', 123.4504, UnitLength::LONG, "123,45 fois l’accélération de pesanteur terrestre" ],
			[ 'fr', 'digital_gigabyte', 123.4504, UnitLength::LONG, "123,45 gigaoctets" ],
			[ 'fr', 'digital_gigabyte', 123.4504, UnitLength::SHORT, "123,45 Go" ],
			[ 'fr', 'digital_gigabyte', 123.4504, UnitLength::NARROW, "123,45Go" ],
			[ 'fr', 'duration_hour', 123.4504, UnitLength::LONG, "123,45 heures" ],
			[ 'fr', 'duration_hour', 123.4504, UnitLength::SHORT, "123,45 h" ],
			[ 'fr', 'duration_hour', 123.4504, UnitLength::NARROW, "123,45h" ],

		];
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	#[DataProvider('provide_test_format_compound')]
	public function test_format_compound(
		string $locale,
		float|int|string $number,
		string $number_unit,
		string $per_unit,
		UnitLength $length,
		string $expected
	): void {
		$actual = $this->units_for($locale)->format_compound($number, $number_unit, $per_unit, $length);

		$this->assertSame($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_format_compound(): array
	{
		return [

			[ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::LONG, "12.345 liters per hour" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::SHORT, "12.345 L/h" ],
			[ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::NARROW, "12.345L/h" ],

			[ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::LONG, "12,345 litres par heure" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::SHORT, "12,345 l/h" ],
			[ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::NARROW, "12,345l/h" ],

			[ 'fr', 12.345, 'volume-liter', 'area-square-meter', UnitLength::LONG, "12,345 litres par mètre carré"],
			[ 'fr', 12.345, 'angle-revolution', 'length-light-year', UnitLength::LONG, "12,345 tours par années-lumière"],

		];
	}

	#[DataProvider('provide_test_format_sequence')]
	public function test_format_sequence(string $locale, callable $sequence, string $expected): void
	{
		$actual = $sequence($this->units_for($locale));

		$this->assertStringSame($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_format_sequence(): array
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
					->length_foot(3)
					->length_inch(2)
					->as_narrow;

			}, "3′ 2″" ],

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

	#[DataProvider('provide_name_for')]
	public function test_name_for(string $unit, UnitLength $length, string $expected_name): void
	{
		$actual = $this->units_for('fr')->name_for($unit, $length);

		$this->assertSame($expected_name, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_name_for(): array
	{
		return [

			[ 'angle_degree', UnitLength::LONG, "degrés" ],
			[ 'angle_degree', UnitLength::SHORT, "°" ],
			[ 'angle_degree', UnitLength::NARROW, "°" ],
			[ 'digital-megabyte', UnitLength::LONG, "mégaoctets" ],
			[ 'digital-megabyte', UnitLength::SHORT, "Mo" ],
			[ 'digital-megabyte', UnitLength::NARROW, "Mo" ],

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
	public function should_fail_with_undefined_unit(): void
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

	private function units_for(string $locale): Units
	{
		return new Units(self::$locales[$locale]); // @phpstan-ignore-line
	}
}
