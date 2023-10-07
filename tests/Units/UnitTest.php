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

use ICanBoogie\CLDR\UnitLength;
use ICanBoogie\CLDR\Units;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnitTest extends TestCase
{
	#[DataProvider('provide_test_properties')]
	public function test_properties(string $unit, string $property, UnitLength $length, string $expected): void
	{
		$units = $this->getMockBuilder(Units::class)
			->disableOriginalConstructor()
			->onlyMethods([ 'name_for' ])
			->getMock();

		$units
			->expects($this->once())
			->method('name_for')
			->with($unit, $length)
			->willReturn($expected);

		$this->assertSame($expected, (new Unit($units, $unit))->$property);
	}

	public static function provide_test_properties(): array
	{
		return [

			[ 'acceleration-g-force', 'name', UnitLength::LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'long_name', UnitLength::LONG, "fois la gravitation terrestre" ],
			[ 'acceleration-g-force', 'short_name', UnitLength::SHORT, "G" ],
			[ 'acceleration-g-force', 'narrow_name', UnitLength::NARROW, "G" ],

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
