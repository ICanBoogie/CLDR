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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumberFormatterTest extends TestCase
{
	#[DataProvider('provide_test_format')]
	public function test_format(string $locale_code, float|int $number, string $pattern, string $expected): void
	{
		$formatter = new NumberFormatter();
		$symbols = get_repository()->locales[$locale_code]->numbers->symbols;
		$this->assertSame($expected, $formatter->format($number, $pattern, $symbols));
	}

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

		];
	}
}
