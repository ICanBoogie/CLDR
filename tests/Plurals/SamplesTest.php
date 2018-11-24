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
class SamplesTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_samples
	 *
	 * @param string $rules
	 * @param array $expected
	 */
	public function test_samples($rules, $expected)
	{
		$samples = Samples::from($rules);

		// can't use assertSame() because of precision issues
		$this->assertEquals($expected, iterator_to_array($samples));
	}

	public function provide_test_samples()
	{
		return [

			[ " @integer 2, 10", [ 2, 10 ] ],
			[ " @integer 2~10", range(2, 10) ],
			[ " @decimal 1.0~1.5", range(1, 1.5, .1) ],
			[ " @decimal 0.0~0.9", [ 0, '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9' ] ],
			[ " @integer 2~10, 100, … @decimal 1.0~1.5, 3.5, …", array_merge(range(2, 10), [ 100 ], range(1, 1.5, .1), [ 3.5 ]) ]

		];
	}

	public function test_same_rules_should_return_same_instance()
	{
		$rules = " @integer 2, 10";
		$samples = Samples::from($rules);

		$this->assertSame($samples, Samples::from($rules));
	}
}
