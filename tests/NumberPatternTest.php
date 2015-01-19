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

class NumberPatternTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @param $pattern
	 * @param array $properties Expected properties
	 *
	 * @dataProvider provide_test_properties
	 */
	public function test_properties($pattern, array $properties)
	{
		$instance = NumberPattern::from($pattern);
		$this->assertSame($properties, $instance->format);

		foreach ($properties as $property => $value)
		{
			$this->assertSame($value, $instance->$property);
		}
	}

	public function provide_test_properties()
	{
		$default = [

			'positive_prefix' => '',
			'positive_suffix' => '',
			'negative_prefix' => '',
			'negative_suffix' => '',
			'multiplier' => 1,
			'decimal_digits' => 0,
			'max_decimal_digits' => 0,
			'integer_digits' => 0,
			'group_size1' => 0,
			'group_size2' => 0

		];

		return [

			[ "#,##0.###", array_merge($default, [

				'negative_prefix' => "-",
				'max_decimal_digits' => 3,
				'integer_digits' => 1,
				'group_size1' => 3

			]) ],

			[ "#,##0.00 ¤;(#,##0.00 ¤)", array_merge($default, [

				'positive_suffix' => " ¤",
			    'negative_prefix' => "(",
			    'negative_suffix' => " ¤)",
				'decimal_digits' => 2,
				'max_decimal_digits' => 2,
				'integer_digits' => 1,
				'group_size1' => 3

			]) ],

			[ "#,##0.##", array_merge($default, [

				'negative_prefix' => "-",
				'max_decimal_digits' => 2,
				'integer_digits' => 1,
				'group_size1' => 3

			]) ],

			[ "#,##0 %", array_merge($default, [

			    'positive_suffix' => ' %',
			    'negative_prefix' => '-',
			    'negative_suffix' => ' %',
			    'multiplier' => 100,
			    'integer_digits' => 1,
			    'group_size1' => 3

			]) ],

			[ "#,##0.### %", array_merge($default, [

				'positive_suffix' => " %",
				'negative_prefix' => "-",
				'negative_suffix' => " %",
				'multiplier' => 100,
				'max_decimal_digits' => 3,
				'integer_digits' => 1,
				'group_size1' => 3

			]) ],

			[ "#,##0.### ‰", array_merge($default, [

				'positive_suffix' => " ‰",
				'negative_prefix' => "-",
				'negative_suffix' => " ‰",
				'multiplier' => 1000,
				'max_decimal_digits' => 3,
				'integer_digits' => 1,
				'group_size1' => 3

			]) ]

		];
	}
}
