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

class DateFormatterTest extends \PHPUnit_Framework_TestCase
{
	static private $formatters = array();

	static public function setupBeforeClass()
	{
		$repository = get_repository();

		self::$formatters['en'] = new DateFormatter($repository->locales['en']->calendar);
		self::$formatters['fr'] = new DateFormatter($repository->locales['fr']->calendar);
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
		return [

			[ 'en', '2013-11-05 21:22:23', 'full', 'Tuesday, November 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', 'long', 'November 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', 'medium', 'Nov 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', 'short', '11/5/13' ],

			[ 'fr', '2013-11-05 21:22:23', 'full', 'mardi 5 novembre 2013' ],
			[ 'fr', '2013-11-05 21:22:23', 'long', '5 novembre 2013' ],
			[ 'fr', '2013-11-05 21:22:23', 'medium', '5 nov. 2013' ],
			[ 'fr', '2013-11-05 21:22:23', 'short', '05/11/2013' ],

			# datetime patterns must be supported too
			[ 'en', '2013-11-05 21:22:23', ':GyMMMEd', 'Tue, Nov 5, 2013 AD' ],
			[ 'fr', '2013-11-05 21:22:23', 'd MMMM y', '5 novembre 2013' ]

		];
	}
}
