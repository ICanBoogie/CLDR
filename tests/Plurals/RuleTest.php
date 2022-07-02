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
final class RuleTest extends TestCase
{
	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param number $number
	 */
	public function test_cases(string $rule, $number, bool $expected): void
	{
		$this->assertSame($expected, Rule::from($rule)->validate($number));
	}

	/**
	 * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-core/supplemental/plurals.json
	 */
	public function provide_test_cases(): array
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
			[ $r3, "1003.0", true ],
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
			[ $r7, "1000.2", true ],

			[ "e = 0", "1.0000001c6", false ],
			[ "e = 0 or e != 0..5", "1.0000001c6", true ],
			[ $r8 = "e = 0 and i != 0 and i % 1000000 = 0 and v = 0 or e != 0..5", "1.0000001c6", true ],
			[ $r8, "1.1c6", true ],
			[ $r8, "1c6", true ],
			[ $r8, 1000000, true ],
			[ $r8, 1000000.1, false ],

		];
	}
}
