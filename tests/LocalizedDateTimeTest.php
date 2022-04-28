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

use DateTime;

class LocalizedDateTimeTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var LocalizedDateTime[]
	 */
	static private $localized_dates;

	static public function setupBeforeClass()
	{
		self::$localized_dates['en'] = new LocalizedDateTime(new DateTime('2013-11-04 20:21:22 UTC'), get_repository()->locales['en']);
		self::$localized_dates['fr'] = new LocalizedDateTime(new DateTime('2013-11-04 20:21:22 UTC'), get_repository()->locales['fr']);
		self::$localized_dates['zh'] = new LocalizedDateTime(new DateTime('2013-11-04 20:21:22 UTC'), get_repository()->locales['zh']);
	}

	public function test_get_target()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(DateTime::class, $ld->target);
	}

	public function test_get_locale()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(Locale::class, $ld->locale);
	}

	/**
	 * @expectedException \ICanBoogie\PropertyNotReadable
	 */
	public function test_get_options()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInternalType('array', $ld->options);
	}

	public function test_get_formatter()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(DateTimeFormatter::class, $ld->formatter);
	}

	/**
	 * @dataProvider provide_test_as
	 *
	 * @param string $locale
	 * @param string $as
	 * @param string $expected
	 */
	public function test_as($locale, $as, $expected)
	{
		$property = 'as_' . $as;
		$method = 'format_' . $property;

		$this->assertEquals($expected, self::$localized_dates[$locale]->$property);
		$this->assertEquals($expected, self::$localized_dates[$locale]->$method());
	}

	public function provide_test_as()
	{
		return [

			[ 'en', 'full', "Monday, November 4, 2013 at 8:21:22 PM UTC" ],
			[ 'en', 'long', "November 4, 2013 at 8:21:22 PM UTC" ],
			[ 'en', 'medium', "Nov 4, 2013, 8:21:22 PM" ],
			[ 'en', 'short', "11/4/13, 8:21 PM" ],

			[ 'fr', 'full', "lundi 4 novembre 2013 à 20:21:22 UTC" ],
			[ 'fr', 'long', "4 novembre 2013 à 20:21:22 UTC" ],
			[ 'fr', 'medium', "4 nov. 2013 à 20:21:22" ],
			[ 'fr', 'short', "04/11/2013 20:21" ],

			[ 'zh', 'full', "2013年11月4日星期一 UTC 下午8:21:22" ],
			[ 'zh', 'long', "2013年11月4日 UTC 下午8:21:22" ],
			[ 'zh', 'medium', "2013年11月4日 下午8:21:22" ],
			[ 'zh', 'short', "2013/11/4 下午8:21" ],

		];
	}

	public function test_to_string()
	{
		$this->assertEquals('2013-11-04T20:21:22+00:00', (string) self::$localized_dates['fr']);
	}
}
