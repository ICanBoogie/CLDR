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

use function array_map;
use function range;

/**
 * @group plurals
 */
final class SamplesTest extends TestCase
{
	/**
	 * @dataProvider provide_test_samples
	 *
	 * @param string $rules
	 * @param array $expected
	 */
	public function test_samples(string $rules, array $expected): void
	{
		$samples = Samples::from($rules);
		$expected = array_map('strval', $expected); // Samples are strings.

		$this->assertSame($expected, iterator_to_array($samples));
	}

	public static function provide_test_samples(): array
	{
		return [

			[ " @integer 2, 10", [ 2, 10 ] ],
			[ " @integer 2~10", range(2, 10) ],
			[ " @decimal 1.0~1.5", range(1, 1.5, .1) ],
			[ " @decimal 0.0~0.9", range(0, 0.9, .1) ],
			[ " @integer 2~10, 100, … @decimal 1.0~1.5, 3.5, …", array_merge(range(2, 10), [ 100 ], range(1, 1.5, .1), [ 3.5 ]) ],
			[ " @integer 1000000, 1c6, 2c6, 3c6, 4c6, …", [ '1000000', '1c6', '2c6', '3c6', '4c6' ] ],
			[ " @decimal 1.0000001c6, 1.1c6, 2.0000001c6, 2.1c6, 3.0000001c6, 3.1c6, …", [ '1.0000001c6', '1.1c6', '2.0000001c6', '2.1c6', '3.0000001c6', '3.1c6' ] ],

		];
	}

	public function test_same_rules_should_return_same_instance(): void
	{
		$rules = " @integer 2, 10";
		$samples = Samples::from($rules);

		$this->assertSame($samples, Samples::from($rules));
	}
}
