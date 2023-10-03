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

final class LocalizedNumberFormatterTest extends TestCase
{
	/**
	 * @dataProvider provide_test_format
	 *
	 * @param numeric $number
	 */
	public function test_format(string $locale_code, $number, ?string $pattern, string $expected): void
	{
		$formatter = new NumberFormatter();
		$localized = new LocalizedNumberFormatter($formatter, get_repository()->locales[$locale_code]);

		$this->assertSame($expected, $localized->format($number, $pattern));
	}

	public static function provide_test_format(): array
	{
		return [

			[ 'en',     123,      '#',           "123" ],
			[ 'en',    -123,      '#',          "-123" ],
			[ 'en',     123,      '#;-#',        "123" ],
			[ 'en',    -123,      '#;-#',       "-123" ],
			[ 'en',    4123.37,   '#,#00.#0',  "4,123.37" ],
			[ 'fr',    4123.37,   '#,#00.#0',  "4 123,37" ],
			[ 'fr',   -4123.37,   '#,#00.#0', "-4 123,37" ],
			[ 'en',       .3789,  '#0.#0 %',      "37.89 %" ],
			[ 'fr',       .3789,  '#0.#0 %',      "37,89 %" ],
			[ 'fr', 123456.78,    null,      "123 456,78" ],
			[ 'en', 123456.78,    null,      "123,456.78" ]

		];
	}

	public function test_invoke(): void
	{
		$formatter = new NumberFormatter();
		$localized = new LocalizedNumberFormatter($formatter, get_repository()->locales['fr']);

		$this->assertSame($localized->format(123456.78), $localized(123456.78));
	}
}
