<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\LocalizedNumberFormatter;
use ICanBoogie\CLDR\NumberFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedNumberFormatterTest extends TestCase
{
	#[DataProvider('provide_test_format')]
	public function test_format(string $locale_id, float|int $number, ?string $pattern, string $expected): void
	{
		$formatter = new NumberFormatter();
		$localized = new LocalizedNumberFormatter($formatter, locale_for($locale_id));

		$this->assertSame($expected, $localized->format($number, $pattern));
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_format(): array
	{
		return [

			[ 'en', 123, '#', "123" ],
			[ 'en', -123, '#', "-123" ],
			[ 'en', 123, '#;-#', "123" ],
			[ 'en', -123, '#;-#', "-123" ],
			[ 'en', 4123.37, '#,#00.#0', "4,123.37" ],
			[ 'fr', 4123.37, '#,#00.#0', "4 123,37" ],
			[ 'fr', -4123.37, '#,#00.#0', "-4 123,37" ],
			[ 'en', .3789, '#0.#0 %', "37.89 %" ],
			[ 'fr', .3789, '#0.#0 %', "37,89 %" ],
			[ 'fr', 123456.78, null, "123 456,78" ],
			[ 'en', 123456.78, null, "123,456.78" ]

		];
	}

	public function test_invoke(): void
	{
		$formatter = new NumberFormatter();
		$localized = new LocalizedNumberFormatter($formatter, locale_for('fr'));

		$this->assertSame($localized->format(123456.78), $localized(123456.78));
	}
}
