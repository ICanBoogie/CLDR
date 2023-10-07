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
final class RelationTest extends TestCase
{
	/**
	 * @dataProvider provide_test_cases
	 *
	 * @param float|int $number
	 */
	public function test_cases(string $relation, float|int $number, bool $expected): void
	{
		$operands = Operands::from($number);

		$this->assertSame($expected, Relation::from($relation)->evaluate($operands));
	}

	public static function provide_test_cases(): array
	{
		return [

			[ "", 1, true ],
			[ "n = 1", 1, true ],
			[ "n = 1", 0, false ],
			[ "n != 1", 0, true ],
			[ "n != 1", 1, false ],

			[ "n = 1,2,3", 1, true ],
			[ "n = 1,2,3", 2, true ],
			[ "n = 1,2,3", 3, true ],
			[ "n = 1,2,3", 4, false ],

			[ "n != 1,2,3", 1, false ],
			[ "n != 1,2,3", 2, false ],
			[ "n != 1,2,3", 3, false ],
			[ "n != 1,2,3", 4, true ],

			[ "n = 2..4,15", 3.5, false ],
			[ "n = 2..4,15", 3, true ],
			[ "n != 2..4,15", 3.5, true ],
			[ "n != 2..4,15", 3, false ],

			[ "n % 3 = 1.3", 4.3, true ]

		];
	}
}
