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

use ICanBoogie\DateTime;

class LocalizedDateTimeTest extends \PHPUnit_Framework_TestCase
{
	static private $localized_dates;

	static public function setupBeforeClass()
	{
		self::$localized_dates['en'] = new LocalizedDateTime(new DateTime('2013-11-04 20:21:22 UTC'), get_repository()->locales['en']);
		self::$localized_dates['fr'] = new LocalizedDateTime(new DateTime('2013-11-04 20:21:22 UTC'), get_repository()->locales['fr']);
	}

	public function test_get_target()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf('ICanBoogie\DateTime', $ld->target);
	}

	public function test_get_locale()
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf('ICanBoogie\CLDR\Locale', $ld->locale);
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

		$this->assertInstanceOf('ICanBoogie\CLDR\DateTimeFormatter', $ld->formatter);
	}

	/**
	 * @dataProvider provide_test_as
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

			[ 'fr', 'full', "lundi 4 novembre 2013 20:21:22 UTC" ],
			[ 'fr', 'long', "4 novembre 2013 20:21:22 UTC" ],
			[ 'fr', 'medium', "4 nov. 2013 20:21:22" ],
			[ 'fr', 'short', "04/11/2013 20:21" ],
			[ 'fr', 'db', "2013-11-04 20:21:22" ],

			[ 'en', 'atom', "2013-11-04T20:21:22+00:00" ],
			[ 'en', 'cookie', "Monday, 04-Nov-2013 20:21:22 UTC" ],
			[ 'en', 'iso8601', "2013-11-04T20:21:22Z" ],
			[ 'en', 'rfc822', "Mon, 04 Nov 13 20:21:22 GMT" ],
			[ 'en', 'rfc850', "Monday, 04-Nov-13 20:21:22 UTC" ],
			[ 'en', 'rfc1036', "Mon, 04 Nov 13 20:21:22 +0000" ],
			[ 'en', 'rfc1123', "Mon, 04 Nov 2013 20:21:22 GMT" ],
			[ 'en', 'rfc2822', "Mon, 04 Nov 2013 20:21:22 +0000" ],
			[ 'en', 'rfc3339', "2013-11-04T20:21:22+00:00" ],
			[ 'en', 'rss', "Mon, 04 Nov 2013 20:21:22 +0000" ],
			[ 'en', 'w3c', "2013-11-04T20:21:22+00:00" ],
			[ 'en', 'db', "2013-11-04 20:21:22" ],
			[ 'en', 'number', "20131104202122" ],
			[ 'en', 'date', "2013-11-04" ],
			[ 'en', 'time', "20:21:22" ]

		];
	}

	public function test_to_string()
	{
		$this->assertEquals('2013-11-04T20:21:22Z', (string) self::$localized_dates['fr']);
	}
}
