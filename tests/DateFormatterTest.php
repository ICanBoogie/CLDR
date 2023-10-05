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

use PHPUnit\Framework\TestCase;

final class DateFormatterTest extends TestCase
{
	/**
	 * @var array<string, DateFormatter>
	 */
	static private array $formatters = [];

	static public function setupBeforeClass(): void
	{
		$repository = get_repository();

		self::$formatters['en'] = new DateFormatter($repository->locales['en']->calendar);
		self::$formatters['fr'] = new DateFormatter($repository->locales['fr']->calendar);
	}

	/**
	 * @dataProvider provide_test_format
	 */
	public function test_format(
		string $locale,
		string $datetime,
		string|DateTimeFormatLength|DateTimeFormatId $pattern,
		string $expected
	): void {
		$actual = self::$formatters[$locale]->format($datetime, $pattern);

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_format(): array
	{
		return [

			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::FULL, 'Tuesday, November 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::LONG, 'November 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::MEDIUM, 'Nov 5, 2013' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::SHORT, '11/5/13' ],

			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::FULL, 'mardi 5 novembre 2013' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::LONG, '5 novembre 2013' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::MEDIUM, '5 nov. 2013' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::SHORT, '05/11/2013' ],

			# datetime patterns must be supported too
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatId::from('yMMMEd'), 'Tue, Nov 5, 2013' ],
			[ 'fr', '2013-11-05 21:22:23', 'd MMMM y', '5 novembre 2013' ]

		];
	}
}
