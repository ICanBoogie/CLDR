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

use ICanBoogie\CLDR\Numbers\Symbols;
use PHPUnit\Framework\TestCase;

final class NumbersTest extends TestCase
{
	/**
	 * @dataProvider provide_test_shortcuts
	 */
	public function test_shortcuts(string $locale_code, string $property, string $offset): void
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers_data = $locale['numbers'];
		$numbers = new Numbers($locale, $numbers_data);

		$this->assertSame($numbers_data[$offset], $numbers->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_shortcuts(): array
	{
		return [

			[ 'fr', 'decimal_formats', 'decimalFormats-numberSystem-latn' ],
			[ 'fr', 'scientific_formats', 'scientificFormats-numberSystem-latn' ],
			[ 'fr', 'percent_formats', 'percentFormats-numberSystem-latn' ],
			[ 'fr', 'currency_formats', 'currencyFormats-numberSystem-latn' ],
			[ 'fr', 'misc_patterns', 'miscPatterns-numberSystem-latn' ]

		];
	}

	/**
	 * @dataProvider provide_symbols
	 */
	public function test_symbols(string $locale_code, Symbols $expected): void
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers_data = $locale['numbers'];
		$numbers = new Numbers($locale, $numbers_data);

		$this->assertEquals($expected, $numbers->symbols);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_symbols(): array
	{
		return [

			[ 'fr', new Symbols(
				',',
				' ',
				';',
				'%',
				'-',
				'+',
				'≃',
				'E',
				'×',
				'‰',
				'∞',
				'NaN',
				'.',
				',',
				':'
			) ],
			[ 'en', new Symbols(
				'.',
				',',
				';',
				'%',
				'-',
				'+',
				'~',
				'E',
				'×',
				'‰',
				'∞',
				'NaN',
				'.',
				',',
				':'
			) ],
			[ 'ru', new Symbols(
				',',
				' ',
				';',
				'%',
				'-',
				'+',
				'≈',
				'E',
				'×',
				'‰',
				'∞',
				'не число',
				'.',
				',',
				':'
			) ],
		];
	}

	/**
	 * @dataProvider provide_test_decimal_width_shortcuts
	 */
	public function test_decimal_width_shortcuts(string $locale_code, string $property, string $offset, string $width_offset): void
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers_data = $locale['numbers'];
		$numbers = new Numbers($locale, $numbers_data);

		$this->assertSame($numbers_data[$offset][$width_offset]['decimalFormat'], $numbers->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_decimal_width_shortcuts(): array
	{
		return [

			[ 'fr', 'short_decimal_formats', 'decimalFormats-numberSystem-latn', 'short' ],
			[ 'fr', 'long_decimal_formats', 'decimalFormats-numberSystem-latn', 'long' ]

		];
	}

	/**
	 * @dataProvider provide_test_get_decimal_format
	 */
	public function test_get_decimal_format(string $locale_code, string $expected): void
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers = new Numbers($locale, $locale['numbers']);

		$this->assertEquals($expected, $numbers->decimal_format);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_get_decimal_format(): array
	{
		return [

			[ 'en', "#,##0.###" ],
			[ 'fr', "#,##0.###" ],
			[ 'ja', "#,##0.###" ]

		];
	}
}
