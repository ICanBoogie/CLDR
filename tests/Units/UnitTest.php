<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Units;

use ICanBoogie\CLDR\Units;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
	/**
	 * @dataProvider provide_test_properties
	 *
	 * @param string $unit
	 * @param string $property
	 * @param string $length
	 * @param string $expected
	 */
	public function test_properties($unit, $property, $length, $expected)
	{
		$units = $this->mockUnits(function ($units) use ($unit, $length, $expected) {

			$units->name_for($unit, $length)
				->shouldBeCalled()
				->willReturn($expected);

		});

		$this->assertSame($expected, (new Unit($units, $unit))->$property);
	}

	public function provide_test_properties()
	{
		return [

			[ 'acceleration-g-force', 'name', Units::LENGTH_LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'long_name', Units::LENGTH_LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'short_name', Units::LENGTH_SHORT, "G" ],
			[ 'acceleration-g-force', 'narrow_name', Units::LENGTH_NARROW, "G" ],

		];
	}

	public function test_to_string()
	{
		$unit = uniqid();

		$this->assertSame($unit, (string) new Unit($this->mockUnits(), $unit));
	}

	public function test_per_unit()
	{
		$number = uniqid();
		$unit = uniqid();
		$per_unit = uniqid();
		$length = uniqid();
		$expected = uniqid();

		$units = $this->mockUnits(function ($units) use($number, $unit, $per_unit, $length, $expected) {

			$units->format_combination($number, $unit, $per_unit, $length)
				->shouldBeCalled()
				->willReturn($expected);

		});

		$this->assertSame($expected, (new Unit($units, $unit))->per_unit($number, $per_unit, $length));
	}

	/**
	 * @param callable|null $init
	 *
	 * @return Units
	 */
	private function mockUnits(callable $init = null)
	{
		$units = $this->prophesize(Units::class);

		if ($init)
		{
			$init($units);
		}

		return $units->reveal();
	}
}
