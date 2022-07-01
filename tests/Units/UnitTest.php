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

final class UnitTest extends TestCase
{
	/**
	 * @dataProvider provide_test_properties
	 */
	public function test_properties(string $unit, string $property, string $length, string $expected): void
	{
		$units = $this->mockUnits(function ($units) use ($unit, $length, $expected) {

			$units->name_for($unit, $length)
				->shouldBeCalled()
				->willReturn($expected);

		});

		$this->assertSame($expected, (new Unit($units, $unit))->$property);
	}

	public function provide_test_properties(): array
	{
		return [

			[ 'acceleration-g-force', 'name', Units::LENGTH_LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'long_name', Units::LENGTH_LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'short_name', Units::LENGTH_SHORT, "G" ],
			[ 'acceleration-g-force', 'narrow_name', Units::LENGTH_NARROW, "G" ],

		];
	}

	public function test_to_string(): void
	{
		$unit = uniqid();

		$this->assertSame($unit, (string) new Unit($this->mockUnits(), $unit));
	}

	/**
	 * @param callable|null $init
	 */
	private function mockUnits(callable $init = null): Units
	{
		$units = $this->prophesize(Units::class);

		if ($init)
		{
			$init($units);
		}

		return $units->reveal();
	}
}
