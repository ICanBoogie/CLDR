<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\DateFormatter;
use ICanBoogie\CLDR\DateTimeFormatId;
use ICanBoogie\CLDR\DateTimeFormatLength;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateFormatterTest extends TestCase
{
	/**
	 * @var array<string, DateFormatter>
	 */
	private static array $formatters = [];

	public static function setupBeforeClass(): void
	{
		self::$formatters['en'] = new DateFormatter(locale_for('en')->calendar);
		self::$formatters['fr'] = new DateFormatter(locale_for('fr')->calendar);
	}

	#[DataProvider('provide_test_format')]
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
