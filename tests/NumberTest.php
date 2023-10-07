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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumberTest extends TestCase
{
	public function test_should_return_correct_precision(): void
	{
		$this->assertEquals(3, Number::precision_from(12.123));
	}

	public function test_should_return_zero_precision_if_the_number_is_not_a_decimal(): void
	{
		$this->assertEquals(0, Number::precision_from(12));
	}

	/**
	 * should round a number to the given precision
	 */
	#[DataProvider('provide_test_round_to')]
	public function test_round_to(float|int $number, int $precision, float|int $expected): void
	{
		$this->assertEquals($expected, Number::round_to($number, $precision));
	}

	public static function provide_test_round_to(): array
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
	 */
	#[DataProvider('provide_test_parse')]
	public function test_parse(float|int $number, int $precision, array $expected): void
	{
		$this->assertSame($expected, Number::parse($number, $precision));
	}

	public static function provide_test_parse(): array
	{
		return [

			[ 12,    0, [ 12,  null ] ],
			[ 12.2,  0, [ 12,  null ] ],
			[ 12.5,  0, [ 13,  null ] ],
			[ 12.25, 1, [ 12,   '3' ] ],
			[ 12.25, 2, [ 12,  '25' ] ],
			[ 12.25, 3, [ 12, '250' ] ],

		];
	}
}
