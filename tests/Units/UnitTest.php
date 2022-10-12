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

use function var_dump;

use const PHP_VERSION_ID;

final class UnitTest extends TestCase
{
	/**
	 * @dataProvider provide_test_properties
	 */
	public function test_properties(string $unit, string $property, string $length, string $expected): void
	{
		if (PHP_VERSION_ID < 70200)
		{
			$units = $this->getMockBuilder(Units::class)
				->disableOriginalConstructor()
				->setMethods([ 'name_for' ])
				->getMock();
		}
		else
		{
			$units = $this->getMockBuilder(Units::class)
				->disableOriginalConstructor()
				->onlyMethods([ 'name_for' ])
				->getMock();
		}

		$units
			->expects($this->once())
			->method('name_for')
			->with($unit, $length)
			->willReturn($expected);

		$this->assertSame($expected, (new Unit($units, $unit))->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
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
		$units = $this->getMockBuilder(Units::class)
			->disableOriginalConstructor()
			->getMock();

		$this->assertSame($unit, (string) new Unit($units, $unit));
	}
}
