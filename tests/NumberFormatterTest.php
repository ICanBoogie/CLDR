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
	 */
	public function test_round_to($number, $precision, $expected)
	{
		$this->assertEquals($expected, NumberFormatter::round_to($number, $precision));
	}

	public function provide_test_round_to()
	{
		return array(

			array( 12, 0, 12 ),
			array( 12.2, 0, 12 ),
			array( 12.5, 0, 13 ),
			array( 12.25, 1, 12.3 ),
			array( 12.25, 2, 12.25 ),
			array( 12.25, 3, 12.25 ),

		);
	}

	/**
	 * should round and split the given number by decimal
	 *
	 * @dataProvider provide_test_parse_number
	 */
	public function test_parse_number($number, $precision, $expected)
	{
		$this->assertEquals($expected, NumberFormatter::parse_number($number, $precision));
	}

	public function provide_test_parse_number()
	{
		return array(

			array( 12, 0, array('12')),
			array( 12.2, 0, array('12')),
			array( 12.5, 0, array('13')),
			array( 12.25, 1, array('12', '3')),
			array( 12.25, 2, array('12', '25')),
			array( 12.25, 3, array('12', '250')),

		);
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format($locale_code, $number, $pattern, $expected)
	{
		$formatter = new NumberFormatter(get_repository()->locales[$locale_code]->numbers);
		$this->assertSame($expected, $formatter->format($number, $pattern));
	}

	public function provide_test_format()
	{
		return array(

			array( 'en',   123,      '#',           "123"      ),
			array( 'en',  -123,      '#',          "-123"      ),
			array( 'en',   123,      '#;-#',        "123"      ),
			array( 'en',  -123,      '#;-#',       "-123"      ),
			array( 'en',  4123.37,   '#,#00.#0',  "4,123.37"   ),
			array( 'fr',  4123.37,   '#,#00.#0',  "4 123,37"   ),
			array( 'fr', -4123.37,   '#,#00.#0', "-4 123,37"   ),
			array( 'en',      .3789, '#0.#0 %',      "37.89 %" ),
			array( 'fr',      .3789, '#0.#0 %',      "37,89 %" ),

		);
	}
}
