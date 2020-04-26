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
class RuleTest extends TestCase
{
	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param string $rule
	 * @param number $number
	 * @param bool $expected
	 */
	public function test_cases($rule, $number, $expected)
	{
		$this->assertSame($expected, Rule::from($rule)->validate($number));
	}

	public function provide_test_cases()
	{
		$r1 = "n = 0 or n != 1 and n % 100 = 1..19";
		$r2 = "n % 10 = 2..4 and n % 100 != 12..14";
		$r3 = "n % 10 = 3..4,9 and n % 100 != 10..19,70..79,90..99";
		$r4 = "n % 10 = 2..4 and n % 100 != 12..14";
		$r5 = "i = 2..4 and v = 0";
		$r6 = "v = 0 and i % 100 = 3..4 or f % 100 = 3..4";
		$r7 = "v = 0 and i % 10 = 2..4 and i % 100 != 12..14 or f % 10 = 2..4 and f % 100 != 12..14";

		return [

			[ $r1,   0, true ],
			[ $r1, 119, true ],
			[ $r1, 219, true ],
			[ $r1, 319, true ],
			[ $r1,   1, false ],

			[ $r2,   12, false ],
			[ $r2,  113, false ],
			[ $r2,  214, false ],

			[ $r3, 3, true ],
			[ $r3, 29, true ],
			[ $r3, 1003.0, true ],
			[ $r3, 8, false ],

			[ $r4,   2, true ],
			[ $r4,  11, false ],
			[ $r4,  12, false ],
			[ $r4, 214, false ],

			[ $r5, 2, true ],
			[ $r5, 4, true ],
			[ $r5, 5, false ],

			[ $r6, 3, true ],
			[ $r6, 604, true ],
			[ $r6, 5.4, true ],

			[ $r7, 22, true ],
			[ $r7, 1002, true ],
			[ $r7, 3.2, true ],
			[ $r7, 1000.2, true ],

		];
	}
}
