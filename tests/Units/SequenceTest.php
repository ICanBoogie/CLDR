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
use PHPUnit\Framework\TestCase;

final class SequenceTest extends TestCase
{
	public function test_format(): void
	{
		$unit = "digital-megabyte";
		$method = strtr($unit, '-', '_');
		$number = mt_rand(100, 200);
		$expected = uniqid();
		$length = UnitLength::NARROW;

		$units = $this->getMockBuilder(Units::class)
			->onlyMethods([ 'assert_is_unit', 'format_sequence' ])
			->disableOriginalConstructor()
			->getMock();
		$units
			->expects($this->once())
			->method('assert_is_unit')
			->with($unit);
		$units
			->expects($this->once())
			->method('format_sequence')
			->with([ $unit => $number ], $length)
			->willReturn($expected);

		$unit = new Sequence($units);
		$this->assertSame($expected, $unit->$method($number)->format($length));
	}

	public function test_to_string(): void
	{
		$unit = "digital-megabyte";
		$method = strtr($unit, '-', '_');
		$number = mt_rand(100, 200);
		$expected = uniqid();
		$length = Units::DEFAULT_LENGTH;

		$units = $this->getMockBuilder(Units::class)
			->onlyMethods([ 'assert_is_unit', 'format_sequence' ])
			->disableOriginalConstructor()
			->getMock();
		$units
			->expects($this->once())
			->method('assert_is_unit')
			->with($unit);
		$units
			->expects($this->once())
			->method('format_sequence')
			->with([ $unit => $number ], $length)
			->willReturn($expected);

		$unit = new Sequence($units);
		$this->assertSame($expected, (string)$unit->$method($number));
	}
}
