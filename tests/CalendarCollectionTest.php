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

class CalendarCollectionTest extends TestCase
{
	/**
	 * @var CalendarCollection
	 */
	static private $collection;

	static public function setupBeforeClass(): void
	{
		self::$collection = get_repository()->locales['fr']->calendars;
	}

	public function test_offsetExists()
	{
		$this->expectException(BadMethodCallException::class);
		self::$collection->offsetExists('gregorian');
	}

	public function test_offsetSet()
	{
		$this->expectException(OffsetNotWritable::class);
		self::$collection['gregorian'] = null;
	}

	public function test_offsetUnset()
	{
		$this->expectException(OffsetNotWritable::class);
		unset(self::$collection['gregorian']);
	}

	/**
	 * @dataProvider provide_test_get
	 *
	 * @param string $calendar_id
	 */
	public function test_get($calendar_id)
	{
		$calendar = self::$collection[$calendar_id];
		$this->assertInstanceOf(Calendar::class, $calendar);
	}

	public function provide_test_get()
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
