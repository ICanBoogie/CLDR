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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTimeAccessorTest extends TestCase
{
	#[DataProvider('provide_test_properties')]
	public function test_properties(string $property, int $expected): void
	{
		$datetime = new \DateTime("2016-09-17T12:22:32+02:00");

		$this->assertSame($expected, (new DateTimeAccessor($datetime))->$property);
	}

	public static function provide_test_properties(): array
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

	public function test_should_throw_exception_accessing_undefined_property(): void
	{
		$this->expectException(LogicException::class);
		$property = uniqid();

		$this->expectException(\LogicException::class);
		(new DateTimeAccessor(new \DateTime))->$property;
	}
}
