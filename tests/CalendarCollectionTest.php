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

class CalendarCollectionTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var CalendarCollection
	 */
	static private $collection;

	static public function setupBeforeClass()
	{
		self::$collection = get_repository()->locales['fr']->calendars;
	}

	/**
	 * @expectedException \BadMethodCallException
	 */
	public function test_offsetExists()
	{
		self::$collection->offsetExists('gregorian');
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetSet()
	{
		self::$collection['gregorian'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetUnset()
	{
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
