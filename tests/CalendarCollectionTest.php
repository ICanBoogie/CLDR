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

class CalendarCollectionTest extends \PHPUnit_Framework_TestCase
{
	static private $collection;

	static public function setupBeforeClass()
	{
		self::$collection = get_repository()->locales['fr']->calendars;
	}

	/**
	 * @expectedException BadMethodCallException
	 */
	public function test_offsetExists()
	{
		isset(self::$collection['gregorian']);
	}

	/**
	 * @expectedException ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetSet()
	{
		self::$collection['gregorian'] = null;
	}

	/**
	 * @expectedException ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetUnset()
	{
		unset(self::$collection['gregorian']);
	}

	/**
	 * @dataProvider provide_test_get
	 */
	public function test_get($calendar_id)
	{
		$calendar = self::$collection[$calendar_id];
		$this->assertInstanceOf('ICanBoogie\CLDR\Calendar', $calendar);
	}

	public function provide_test_get()
	{
		return array
		(
			array('buddhist'),
			array('chinese'),
			array('coptic'),
			array('dangi'),
			array('ethiopic-amete-alem'),
			array('ethiopic'),
			array('generic'),
			array('gregorian'),
			array('hebrew'),
			array('indian'),
			array('islamic-civil'),
			array('islamic-rgsa'),
			array('islamic-tbla'),
			array('islamic-umalqura'),
			array('islamic'),
			array('japanese'),
			array('persian'),
			array('roc')
		);
	}
}