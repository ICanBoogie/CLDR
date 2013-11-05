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

class TimeFormatterTest extends \PHPUnit_Framework_TestCase
{
	static private $formatters = array();

	static public function setupBeforeClass()
	{
		$repository = get_repository();

		self::$formatters['en'] = new TimeFormatter($repository->locales['en']->calendar);
		self::$formatters['fr'] = new TimeFormatter($repository->locales['fr']->calendar);
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format($locale, $datetime, $pattern, $expected)
	{
		$this->assertEquals($expected, self::$formatters[$locale]->format($datetime, $pattern));
	}

	public function provide_test_format()
	{
		return array
		(
			array('en', '2013-11-05 21:22:23', 'full', '9:22:23 PM CET'),
			array('en', '2013-11-05 21:22:23', 'long', '9:22:23 PM CET'),
			array('en', '2013-11-05 21:22:23', 'medium', '9:22:23 PM'),
			array('en', '2013-11-05 21:22:23', 'short', '9:22 PM'),

			array('fr', '2013-11-05 21:22:23', 'full', '21:22:23 CET'),
			array('fr', '2013-11-05 21:22:23', 'long', '21:22:23 CET'),
			array('fr', '2013-11-05 21:22:23', 'medium', '21:22:23'),
			array('fr', '2013-11-05 21:22:23', 'short', '21:22'),

			# datetime patterns must be supported too
			array('en', '2013-11-05 21:22:23', ':GyMMMEd', 'Tue, Nov 5, 2013 AD'),
			array('fr', '2013-11-05 21:22:23', 'd MMMM y', '5 novembre 2013')
		);
	}
}