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
use ICanBoogie\PropertyNotReadable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedDateTimeTest extends TestCase
{
	/**
	 * @var array<string, LocalizedDateTime>
	 */
	static private array $localized_dates;

	static public function setupBeforeClass(): void
	{
		$datetime = new DateTime('2013-11-04 20:21:22 UTC');

		self::$localized_dates['en'] = new LocalizedDateTime($datetime, get_repository()->locales['en']);
		self::$localized_dates['fr'] = new LocalizedDateTime($datetime, get_repository()->locales['fr']);
		self::$localized_dates['zh'] = new LocalizedDateTime($datetime, get_repository()->locales['zh']);
	}

	public function test_get_target(): void
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(DateTime::class, $ld->target);
	}

	public function test_get_locale(): void
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(Locale::class, $ld->locale);
	}

	public function test_get_options(): void
	{
		$ld = self::$localized_dates['en'];

		$this->expectException(PropertyNotReadable::class);
		$this->assertIsArray($ld->options); // @phpstan-ignore-line
	}

	public function test_get_formatter(): void
	{
		$ld = self::$localized_dates['en'];

		$this->assertInstanceOf(DateTimeFormatter::class, $ld->formatter);
	}

	#[DataProvider('provide_test_as')]
	public function test_as(string $locale, DateTimeFormatLength $as, string $expected): void
	{
		$property = 'as_' . $as->value;
		$method = 'format_' . $property;

		$this->assertEquals($expected, self::$localized_dates[$locale]->$property);
		$this->assertEquals($expected, self::$localized_dates[$locale]->$method());
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_as(): array
	{
		return [

			[ 'en', DateTimeFormatLength::FULL, "Monday, November 4, 2013 at 8:21:22 PM UTC" ],
			[ 'en', DateTimeFormatLength::LONG, "November 4, 2013 at 8:21:22 PM UTC" ],
			[ 'en', DateTimeFormatLength::MEDIUM, "Nov 4, 2013, 8:21:22 PM" ],
			[ 'en', DateTimeFormatLength::SHORT, "11/4/13, 8:21 PM" ],

			[ 'fr', DateTimeFormatLength::FULL, "lundi 4 novembre 2013 à 20:21:22 UTC" ],
			[ 'fr', DateTimeFormatLength::LONG, "4 novembre 2013 à 20:21:22 UTC" ],
			[ 'fr', DateTimeFormatLength::MEDIUM, "4 nov. 2013, 20:21:22" ],
			[ 'fr', DateTimeFormatLength::SHORT, "04/11/2013 20:21" ],

			[ 'zh', DateTimeFormatLength::FULL, "2013年11月4日星期一 UTC 20:21:22" ],
			[ 'zh', DateTimeFormatLength::LONG, "2013年11月4日 UTC 20:21:22" ],
			[ 'zh', DateTimeFormatLength::MEDIUM, "2013年11月4日 20:21:22" ],
			[ 'zh', DateTimeFormatLength::SHORT, "2013/11/4 20:21" ],

		];
	}

	#[DataProvider('provide_format_with_id')]
	public function test_format_with_id(string $locale, string $id, string $expected): void
	{
		$actual = self::$localized_dates[$locale]->format(DateTimeFormatId::from($id));

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_format_with_id(): array
	{
		return [

			[ 'en', 'yMMMEd', 'Mon, Nov 4, 2013' ],
			[ 'en', 'yMEd', 'Mon, 11/4/2013' ],

		];
	}

	public function test_to_string(): void
	{
		$this->assertEquals('2013-11-04T20:21:22+00:00', (string) self::$localized_dates['fr']);
	}
}
