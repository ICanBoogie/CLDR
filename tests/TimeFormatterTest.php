<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\DateTimeFormatId;
use ICanBoogie\CLDR\DateTimeFormatLength;
use ICanBoogie\CLDR\TimeFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TimeFormatterTest extends TestCase
{
	/**
	 * @var array<string, TimeFormatter>
	 */
	private static array $formatters = [];

	public static function setupBeforeClass(): void
	{
		self::$formatters['en'] = new TimeFormatter(locale_for('en')->calendar);
		self::$formatters['fr'] = new TimeFormatter(locale_for('fr')->calendar);
	}

	#[DataProvider('provide_test_format')]
	public function test_format(
		string $locale_id,
		string $datetime,
		string|DateTimeFormatLength|DateTimeFormatId $pattern,
		string $expected
	): void {
		$actual = self::$formatters[$locale_id]->format($datetime, $pattern);

		$this->assertEquals($expected, $actual);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_format(): array
	{
		return [

			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::FULL, '9:22:23 PM CET' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::LONG, '9:22:23 PM CET' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::MEDIUM, '9:22:23 PM' ],
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatLength::SHORT, '9:22 PM' ],

			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::FULL, '21:22:23 CET' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::LONG, '21:22:23 CET' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::MEDIUM, '21:22:23' ],
			[ 'fr', '2013-11-05 21:22:23', DateTimeFormatLength::SHORT, '21:22' ],

			# datetime patterns must be supported too
			[ 'en', '2013-11-05 21:22:23', DateTimeFormatId::from('yMMMEd'), 'Tue, Nov 5, 2013' ],
			[ 'fr', '2013-11-05 21:22:23', 'd MMMM y', '5 novembre 2013' ]

		];
	}
}
