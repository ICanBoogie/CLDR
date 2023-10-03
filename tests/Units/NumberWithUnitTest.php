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
use ICanBoogie\CLDR\Units;
use PHPUnit\Framework\TestCase;

use function ICanBoogie\CLDR\get_repository;

final class NumberWithUnitTest extends TestCase
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

	public function test_to_string(): void
	{
		$stu = new NumberWithUnit(123.4504, 'digital-gigabyte', $this->units_for('fr'));

		$this->assertSame("123,45 gigaoctets", (string) $stu);
	}

	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param float|int $number
	 */
	public function test_cases(string $locale, string $unit, $number, string $length, string $expected): void
	{
		$stu = new NumberWithUnit($number, $unit, $this->units_for($locale));

		$this->assertSame($expected, $stu->{ 'as_' . $length });
	}

	public static function provide_test_cases(): array
	{
		return [

			[ 'fr', 'acceleration-g-force', 123.4504, Units::LENGTH_LONG, "123,45 fois l’accélération de pesanteur terrestre" ],
			[ 'fr', 'digital-gigabyte', 123.4504, Units::LENGTH_LONG, "123,45 gigaoctets" ],
			[ 'fr', 'digital-gigabyte', 123.4504, Units::LENGTH_SHORT, "123,45 Go" ],
			[ 'fr', 'digital-gigabyte', 123.4504, Units::LENGTH_NARROW, "123,45Go" ],
			[ 'fr', 'duration-hour', 123.4504, Units::LENGTH_LONG, "123,45 heures" ],
			[ 'fr', 'duration-hour', 123.4504, Units::LENGTH_SHORT, "123,45 h" ],
			[ 'fr', 'duration-hour', 123.4504, Units::LENGTH_NARROW, "123,45h" ],

		];
	}

	/**
	 * @dataProvider provide_per
	 *
	 * @param float|int $number
	 */
	public function test_per(
		string $locale,
		$number,
		string $number_unit,
		string $per_unit,
		string $length,
		string $expected
	): void
	{
		$stu = new NumberWithUnit($number, $number_unit, $this->units_for($locale));

		$this->assertSame(
			$expected,
			$stu->per($per_unit)->{ 'as_' . $length }
		);
	}

	public static function provide_per(): array
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

	private function units_for($locale): Units
	{
		return self::$locales[$locale]->units;
	}
}
