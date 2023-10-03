<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Plurals;

use PHPUnit\Framework\TestCase;

/**
 * @group plurals
 */
final class OperandsTest extends TestCase
{
	/**
	 * @dataProvider provide_test_cases
	 */
	public function test_cases(string $number, array $expected): void
	{
		$expected = array_combine([ 'n', 'i', 'v', 'w', 'f', 't', 'e' ], $expected);
		$operands = Operands::from($number);

		$this->assertSame($expected, $operands->to_array());

		foreach ($expected as $property => $value)
		{
			$this->assertSame($value, $operands->$property);
		}
	}

	/**
	 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#table-plural-operand-examples
	 */
	public static function provide_test_cases(): array
	{
		return [

			[  '1',     [   1, 1, 0, 0,   0,  0, 0 ] ],
			[  '1.0',   [   1, 1, 1, 0,   0,  0, 0 ] ],
			[  '1.00',  [   1, 1, 2, 0,   0,  0, 0 ] ],
			[  '1.3',   [   1.3, 1, 1, 1,   3,  3, 0 ] ],
			[  '1.30',  [   1.3, 1, 2, 1,  30,  3, 0 ] ],
			[  '1.03',  [  1.03, 1, 2, 2,   3,  3, 0 ] ],
			[  '1.230', [  1.23, 1, 3, 2, 230, 23, 0 ] ],
			[ '-2.350', [ 2.350, 2, 3, 2, 350, 35, 0 ] ],
			[ '1200000', [ 1200000, 1200000, 0, 0, 0, 0, 0 ] ],
			[  '1.2c6', [ 1200000, 1200000, 0, 0, 0, 0, 6 ] ],
			[  '123c6', [ 123000000, 123000000, 0, 0, 0, 0, 6 ] ],
			[  '123c5', [ 12300000, 12300000, 0, 0, 0, 0, 5 ] ],
			[  '1200.50',  [ 1200.5, 1200, 2, 1, 50, 5, 0 ] ],
			[ '1.20050c3', [ 1200.5, 1200, 2, 1, 50, 5, 3 ] ],

		];
	}
}
