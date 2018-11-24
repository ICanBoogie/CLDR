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

/**
 * @group plurals
 */
class OperandsTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param string $number
	 * @param array $expected
	 */
	public function test_cases($number, $expected)
	{
		$expected = array_combine([ 'n', 'i', 'v', 'w', 'f', 't' ], $expected);
		$operands = Operands::from($number);

		$this->assertSame($expected, $operands->to_array());

		foreach ($expected as $property => $value)
		{
			$this->assertSame($value, $operands->$property);
		}
	}

	public function provide_test_cases()
	{
		return [

			[  '1',     [     1, 1, 0, 0,   0,  0 ] ],
			[  '1.0',   [   1.0, 1, 1, 0,   0,  0 ] ],
			[  '1.00',  [   1.0, 1, 2, 0,   0,  0 ] ],
			[  '1.3',   [   1.3, 1, 1, 1,   3,  3 ] ],
			[  '1.30',  [   1.3, 1, 2, 1,  30,  3 ] ],
			[  '1.03',  [  1.03, 1, 2, 2,   3,  3 ] ],
			[  '1.230', [  1.23, 1, 3, 2, 230, 23 ] ],
			[ '-2.350', [ 2.350, 2, 3, 2, 350, 35 ] ],

		];
	}
}
