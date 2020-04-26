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

use LogicException;
use PHPUnit\Framework\TestCase;

class DateTimeAccessorTest extends TestCase
{
	/**
	 * @dataProvider provide_test_properties
	 *
	 * @param string $property
	 * @param string $expected
	 */
	public function test_properties($property, $expected)
	{
		$datetime = new \DateTime("2016-09-17T12:22:32+02:00");

		$this->assertSame($expected, (new DateTimeAccessor($datetime))->$property);
	}

	public function provide_test_properties()
	{
		return [

			[ 'year', 2016 ],
			[ 'month', 9 ],
			[ 'day', 17 ],
			[ 'hour', 12 ],
			[ 'minute', 22 ],
			[ 'second', 32 ],
			[ 'quarter', 3 ],
			[ 'week', 37 ],
			[ 'year_day', 261 ],
			[ 'weekday', 6 ],

		];
	}

	public function test_should_throw_exception_accessing_undefined_property()
	{
		$this->expectException(LogicException::class);
		$property = uniqid();

		$this->expectException(\LogicException::class);
		(new DateTimeAccessor(new \DateTime))->$property;
	}
}
