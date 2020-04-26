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

use PHPUnit\Framework\TestCase;

class NumbersTest extends TestCase
{
	/**
	 * @dataProvider provide_test_shortcuts
	 *
	 * @param string $locale_code
	 * @param string $property
	 * @param string $offset
	 */
	public function test_shortcuts($locale_code, $property, $offset)
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers_data = $locale['numbers'];
		$numbers = new Numbers($locale, $numbers_data);

		$this->assertSame($numbers_data[$offset], $numbers->$property);
	}

	public function provide_test_shortcuts()
	{
		return [

			[ 'fr', 'symbols', 'symbols-numberSystem-latn' ],
			[ 'fr', 'decimal_formats', 'decimalFormats-numberSystem-latn' ],
			[ 'fr', 'scientific_formats', 'scientificFormats-numberSystem-latn' ],
			[ 'fr', 'percent_formats', 'percentFormats-numberSystem-latn' ],
			[ 'fr', 'currency_formats', 'currencyFormats-numberSystem-latn' ],
			[ 'fr', 'misc_patterns', 'miscPatterns-numberSystem-latn' ]

		];
	}

	/**
	 * @dataProvider provide_test_decimal_width_shortcuts
	 *
	 * @param string $locale_code
	 * @param string $property
	 * @param string $offset
	 * @param string $width_offset
	 */
	public function test_decimal_width_shortcuts($locale_code, $property, $offset, $width_offset)
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers_data = $locale['numbers'];
		$numbers = new Numbers($locale, $numbers_data);

		$this->assertSame($numbers_data[$offset][$width_offset]['decimalFormat'], $numbers->$property);
	}

	public function provide_test_decimal_width_shortcuts()
	{
		return [

			[ 'fr', 'short_decimal_formats', 'decimalFormats-numberSystem-latn', 'short' ],
			[ 'fr', 'long_decimal_formats', 'decimalFormats-numberSystem-latn', 'long' ]

		];
	}

	/**
	 * @dataProvider provide_test_get_decimal_format
	 *
	 * @param string $locale_code
	 * @param string $expected
	 */
	public function test_get_decimal_format($locale_code, $expected)
	{
		$locale = get_repository()->locales[$locale_code];
		$numbers = new Numbers($locale, $locale['numbers']);

		$this->assertEquals($expected, $numbers->decimal_format);
	}

	public function provide_test_get_decimal_format()
	{
		return [

			[ 'en', "#,##0.###" ],
			[ 'fr', "#,##0.###" ],
			[ 'ja', "#,##0.###" ]

		];
	}
}
