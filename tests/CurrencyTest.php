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

final class CurrencyTest extends TestCase
{
	public function test_fraction(): void
	{
		$currency = new Currency(get_repository(), 'EUR');
		$fraction = $currency->fraction;

		$this->assertSame(2, $fraction->digits);
		$this->assertSame(0, $fraction->rounding);
		$this->assertSame($fraction, $currency->fraction);
	}

	/**
	 * @dataProvider provide_fraction_properties
	 */
	public function test_fraction_properties(string $code, string $property, int $expected): void
	{
		$currency = new Currency(get_repository(), $code);

		$this->assertSame($expected, $currency->fraction->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_fraction_properties(): array
	{
		return [

			[ 'EUR', 'digits', 2 ],
			[ 'EUR', 'rounding', 0 ],
			[ 'EUR', 'cash_digits', 2 ],
			[ 'EUR', 'cash_rounding', 0 ],

			[ 'HUF', 'digits', 2 ],
			[ 'HUF', 'rounding', 0 ],
			[ 'HUF', 'cash_digits', 0 ],
			[ 'HUF', 'cash_rounding', 0 ],

			[ 'LYD', 'digits', 3 ],
			[ 'LYD', 'rounding', 0 ],
			[ 'LYD', 'cash_digits', 3 ],
			[ 'LYD', 'cash_rounding', 0 ],

			[ 'DKK', 'digits', 2 ],
			[ 'DKK', 'rounding', 0 ],
			[ 'DKK', 'cash_digits', 2 ],
			[ 'DKK', 'cash_rounding', 50 ],

		];
	}

	public function test_localize(): void
	{
		$currency = new Currency(get_repository(), 'CAD');
		$localized = $currency->localize('fr');

		$this->assertSame('dollar canadien', $localized->name);
		$this->assertSame('$CA', $localized->symbol);
	}
}
