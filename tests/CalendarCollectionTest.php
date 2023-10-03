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

use BadMethodCallException;
use ICanBoogie\OffsetNotWritable;
use PHPUnit\Framework\TestCase;

final class CalendarCollectionTest extends TestCase
{
	/**
	 * @var CalendarCollection
	 */
	static private $collection;

	static public function setupBeforeClass(): void
	{
		self::$collection = get_repository()->locales['fr']->calendars;
	}

	public function test_offsetExists(): void
	{
		$this->expectException(BadMethodCallException::class);
		self::$collection->offsetExists('gregorian');
	}

	public function test_offsetSet(): void
	{
		$this->expectException(OffsetNotWritable::class);
		self::$collection['gregorian'] = null;
	}

	public function test_offsetUnset(): void
	{
		$this->expectException(OffsetNotWritable::class);
		unset(self::$collection['gregorian']);
	}

	/**
	 * @dataProvider provide_test_get
	 */
	public function test_get(string $calendar_id): void
	{
		$calendar = self::$collection[$calendar_id];
		$this->assertInstanceOf(Calendar::class, $calendar);
	}

	public static function provide_test_get(): array
	{
		return [

			[ 'buddhist' ],
			[ 'chinese' ],
			[ 'coptic' ],
			[ 'dangi' ],
			[ 'ethiopic' ],
			[ 'generic' ],
			[ 'gregorian' ],
			[ 'hebrew' ],
			[ 'indian' ],
			[ 'islamic' ],
			[ 'japanese' ],
			[ 'persian' ],
			[ 'roc' ]

		];
	}
}
