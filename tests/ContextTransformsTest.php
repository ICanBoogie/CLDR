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

class ContextTransformsTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_transform
	 *
	 * @param string $str
	 * @param string $expected
	 * @param string $usage
	 * @param string $type
	 * @param array $rules
	 */
	public function test_transform($str, $expected, $usage, $type, $rules)
	{
		$this->assertSame($expected, (new ContextTransforms($rules))->transform($str, $usage, $type));
	}

	public function provide_test_transform()
	{
		return [

			[
				"juin",
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[

				]

			],

			[
				"juin",
				"Juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_TITLECASE_FIRSTWORD

					]
				]

			],

			[
				"juin",
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_NO_CHANGE

					]
				]

			],

			[
				"juin",
				"Juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_ALL => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_TITLECASE_FIRSTWORD

					]
				]

			],

			[
				"juin",
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_ALL => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_NO_CHANGE

					]
				]

			],

			[
				"juin",
				"Juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_ALL => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_NO_CHANGE

					],

					ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_TITLECASE_FIRSTWORD

					]
				]

			],

			[
				"juin",
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE,
				[
					ContextTransforms::USAGE_ALL => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_TITLECASE_FIRSTWORD

					],

					ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW => [

						ContextTransforms::TYPE_STAND_ALONE => ContextTransforms::TRANSFORM_NO_CHANGE

					]
				]

			],

		];
	}

	/**
	 * @expectedException \LogicException
	 */
	public function test_should_throw_exception_on_unknown_transform()
	{
		$usage = ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW;
		$type = ContextTransforms::TYPE_STAND_ALONE;

		(new ContextTransforms([

			 $usage => [

				$type => uniqid()

			]

		]))->transform("juin", $usage, $type);
	}
}
