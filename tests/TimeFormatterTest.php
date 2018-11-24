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

class TimeFormatterTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var TimeFormatter[]
	 */
	static private $formatters = [];

	static public function setupBeforeClass()
	{
		$repository = get_repository();

		self::$formatters['en'] = new TimeFormatter($repository->locales['en']->calendar);
		self::$formatters['fr'] = new TimeFormatter($repository->locales['fr']->calendar);
	}

	/**
	 * @dataProvider provide_test_format
	 *
	 * @param string $locale
	 * @param string $datetime
	 * @param string $pattern
	 * @param string $expected
	 */
	public function test_format($locale, $datetime, $pattern, $expected)
	{
		$this->assertEquals($expected, self::$formatters[$locale]->format($datetime, $pattern));
	}

	public function provide_test_format()
	{
		return [

			[ 'en', '2013-11-05 21:22:23', 'full', '9:22:23 PM CET' ],
			[ 'en', '2013-11-05 21:22:23', 'long', '9:22:23 PM CET' ],
			[ 'en', '2013-11-05 21:22:23', 'medium', '9:22:23 PM' ],
			[ 'en', '2013-11-05 21:22:23', 'short', '9:22 PM' ],

			[ 'fr', '2013-11-05 21:22:23', 'full', '21:22:23 CET' ],
			[ 'fr', '2013-11-05 21:22:23', 'long', '21:22:23 CET' ],
			[ 'fr', '2013-11-05 21:22:23', 'medium', '21:22:23' ],
			[ 'fr', '2013-11-05 21:22:23', 'short', '21:22' ],

			# datetime patterns must be supported too
			[ 'en', '2013-11-05 21:22:23', ':GyMMMEd', 'Tue, Nov 5, 2013 AD' ],
			[ 'fr', '2013-11-05 21:22:23', 'd MMMM y', '5 novembre 2013' ]

		];
	}
}
