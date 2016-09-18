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

class NumberTest extends \PHPUnit_Framework_TestCase
{
	public function test_should_return_correct_precision()
	{
		$this->assertEquals(3, Number::precision_from(12.123));
	}

	public function test_should_return_zero_precision_if_the_number_is_not_a_decimal()
	{
		$this->assertEquals(0, Number::precision_from(12));
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
		$this->assertEquals($expected, Number::round_to($number, $precision));
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
	 * @dataProvider provide_test_parse
	 *
	 * @param number $number
	 * @param int $precision
	 * @param array $expected
	 */
	public function test_parse($number, $precision, $expected)
	{
		$this->assertEquals($expected, Number::parse($number, $precision));
	}

	public function provide_test_parse()
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
}
