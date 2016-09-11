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

class NumberFormatterTest extends \PHPUnit_Framework_TestCase
{
	public function test_should_return_correct_precision()
	{
		$this->assertEquals(3, NumberFormatter::precision_from(12.123));
	}

	public function test_should_return_zero_precision_if_the_number_is_not_a_decimal()
	{
		$this->assertEquals(0, NumberFormatter::precision_from(12));
	}

	/**
	 * should round a number to the given precision
	 *
	 * @dataProvider provide_test_round_to
	 *
	 * @param number $number
	 * @param int $precision
	 * @param number $expected
	 */
	public function test_round_to($number, $precision, $expected)
	{
		$this->assertEquals($expected, NumberFormatter::round_to($number, $precision));
	}

	public function provide_test_round_to()
	{
		return [

			[ 12, 0, 12 ],
			[ 12.2, 0, 12 ],
			[ 12.5, 0, 13 ],
			[ 12.25, 1, 12.3 ],
			[ 12.25, 2, 12.25 ],
			[ 12.25, 3, 12.25 ],

		];
	}

	/**
	 * should round and split the given number by decimal
	 *
	 * @dataProvider provide_test_parse_number
	 *
	 * @param number $number
	 * @param int $precision
	 * @param array $expected
	 */
	public function test_parse_number($number, $precision, $expected)
	{
		$this->assertEquals($expected, NumberFormatter::parse_number($number, $precision));
	}

	public function provide_test_parse_number()
	{
		return [

			[ 12, 0, [ '12' ] ],
			[ 12.2, 0, [ '12' ] ],
			[ 12.5, 0, [ '13' ] ],
			[ 12.25, 1, [ '12', '3' ] ],
			[ 12.25, 2, [ '12', '25' ] ],
			[ 12.25, 3, [ '12', '250' ] ],

		];
	}

	/**
	 * @dataProvider provide_test_format
	 *
	 * @param string $locale_code
	 * @param number $number
	 * @param string $pattern
	 * @param string $expected
	 */
	public function test_format($locale_code, $number, $pattern, $expected)
	{
		$formatter = new NumberFormatter();
		$symbols = get_repository()->locales[$locale_code]->numbers->symbols;
		$this->assertSame($expected, $formatter->format($number, $pattern, $symbols));
	}

	public function provide_test_format()
	{
		return [

			[ 'en',   123,      '#',           "123" ],
			[ 'en',  -123,      '#',          "-123" ],
			[ 'en',   123,      '#;-#',        "123" ],
			[ 'en',  -123,      '#;-#',       "-123" ],
			[ 'en',  4123.37,   '#,#00.#0',  "4,123.37" ],
			[ 'fr',  4123.37,   '#,#00.#0',  "4 123,37" ],
			[ 'fr', -4123.37,   '#,#00.#0', "-4 123,37" ],
			[ 'en',      .3789, '#0.#0 %',      "37.89 %" ],
			[ 'fr',      .3789, '#0.#0 %',      "37,89 %" ],

		];
	}

	public function test_localize()
	{
		$formatter = new NumberFormatter(get_repository());
		$this->assertInstanceOf(LocalizedNumberFormatter::class, $formatter->localize('fr'));
	}
}
