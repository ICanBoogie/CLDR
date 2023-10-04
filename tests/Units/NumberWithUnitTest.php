<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Units;

use ICanBoogie\CLDR\LocaleCollection;
use ICanBoogie\CLDR\StringHelpers;
use ICanBoogie\CLDR\UnitLength;
use ICanBoogie\CLDR\Units;
use PHPUnit\Framework\TestCase;

use function ICanBoogie\CLDR\get_repository;

final class NumberWithUnitTest extends TestCase
{
    use StringHelpers;

	static private LocaleCollection $locales;

	static public function setUpBeforeClass(): void
	{
		self::$locales = get_repository()->locales;
	}

	public function test_to_string(): void
	{
		$stu = new NumberWithUnit(123.4504, 'digital-gigabyte', $this->units_for('fr'));

		$this->assertSame("123,45 gigaoctets", (string) $stu);
	}

	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param float|int|numeric-string $number
	 */
	public function test_cases(
		string $locale,
		string $unit,
		float|int|string $number,
		UnitLength $length,
		string $expected
	): void {
		$stu = new NumberWithUnit($number, $unit, $this->units_for($locale));

		$this->assertSame($expected, $stu->{ 'as_' . $length->value });
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_cases(): array
	{
		return [

			[ 'fr', 'acceleration-g-force', 123.4504, UnitLength::LONG, "123,45 fois l’accélération de pesanteur terrestre" ],
			[ 'fr', 'digital-gigabyte', 123.4504, UnitLength::LONG, "123,45 gigaoctets" ],
			[ 'fr', 'digital-gigabyte', 123.4504, UnitLength::SHORT, "123,45 Go" ],
			[ 'fr', 'digital-gigabyte', 123.4504, UnitLength::NARROW, "123,45Go" ],
			[ 'fr', 'duration-hour', 123.4504, UnitLength::LONG, "123,45 heures" ],
			[ 'fr', 'duration-hour', 123.4504, UnitLength::SHORT, "123,45 h" ],
			[ 'fr', 'duration-hour', 123.4504, UnitLength::NARROW, "123,45h" ],

		];
	}

	/**
	 * @dataProvider provide_per
	 *
	 * @param float|int|numeric-string $number
	 */
	public function test_per(
		string $locale,
		float|int|string $number,
		string $number_unit,
		string $per_unit,
		UnitLength $length,
		string $expected
	): void
	{
		$stu = new NumberWithUnit($number, $number_unit, $this->units_for($locale));

		$this->assertSame(
			$expected,
			$stu->per($per_unit)->{ 'as_' . $length->value }
		);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_per(): array
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

	private function units_for(string $locale): Units
	{
		return self::$locales[$locale]->units;
	}
}
