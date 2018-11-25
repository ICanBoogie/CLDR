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

class NumberFormatterTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_format
	 *
	 * @param string $locale_code
	 * @param number $number
	 * @param string $pattern
	 * @param string $expected
	 */
	public function test_format($locale_code, $number, $pattern, $expected)
	{
		$formatter = new NumberFormatter();
		$symbols = get_repository()->locales[$locale_code]->numbers->symbols;
		$this->assertSame($expected, $formatter->format($number, $pattern, $symbols));
	}

	public function provide_test_format()
	{
		return [

			[ 'en',   123,      '#',           "123" ],
			[ 'en',  -123,      '#',          "-123" ],
			[ 'en',   123,      '#;-#',        "123" ],
			[ 'en',  -123,      '#;-#',       "-123" ],
			[ 'en',  4123.37,   '#,#00.#0',  "4,123.37" ],
			[ 'fr',  4123.37,   '#,#00.#0',  "4 123,37" ],
			[ 'fr', -4123.37,   '#,#00.#0', "-4 123,37" ],
			[ 'en',      .3789, '#0.#0 %',      "37.89 %" ],
			[ 'fr',      .3789, '#0.#0 %',      "37,89 %" ],

		];
	}

	public function test_localize()
	{
		$formatter = new NumberFormatter(get_repository());
		$this->assertInstanceOf(LocalizedNumberFormatter::class, $formatter->localize('fr'));
	}
}
