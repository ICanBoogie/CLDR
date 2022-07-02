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

final class LocalizedCurrencyTest extends TestCase
{
    use StringHelpers;

	/**
	 * @var Currency
	 */
	static private $currency;

	/**
	 * @var LocalizedCurrency
	 */
	static private $localized;

	static public function setUpBeforeClass(): void
	{
		self::$currency = new Currency(get_repository(), 'IEP');
		self::$localized = new LocalizedCurrency(self::$currency, get_repository()->locales['fr']);
	}

	public function test_name(): void
	{
		$this->assertEquals("livre irlandaise", self::$localized->name);
	}

	public function test_name_for(): void
	{
		$this->assertEquals("livre irlandaise", self::$localized->name);
		$this->assertEquals("livre irlandaise", self::$localized->name_for(1));
		$this->assertEquals("livres irlandaises", self::$localized->name_for(10));
	}

	public function test_get_symbol(): void
	{
		$this->assertEquals("£IE", self::$localized->symbol);
	}

	public function test_localize(): void
	{
		$localized = self::$currency->localize('en');
		$this->assertInstanceOf(LocalizedCurrency::class, $localized);
		$this->assertEquals("Irish Pound", $localized->name);
	}

	/**
	 * @dataProvider provide_test_format
	 *
	 * @param float|int $number
	 */
	public function test_format(string $currency_code, string $locale_code, $number, string $expected): void
	{
		$currency = new Currency(get_repository(), $currency_code);
		$localized = $currency->localize($locale_code);
		$this->assertEquals($expected, $localized->format($number));
	}

	public function provide_test_format(): array
	{
		return [

			[ 'IEP', 'fr', 123456.789, "123 456,79 £IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123 456,79 €" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123 456,79 \$US" ],
			[ 'USD', 'en', 123456.789, "\$123,456.79" ],

		];
	}

	/**
	 * @dataProvider provide_test_format_accounting
	 *
	 * @param float|int $number
	 */
	public function test_format_accounting(string $currency_code, string $locale_code, $number, string $expected)
	{
		$currency = new Currency(get_repository(), $currency_code);
		$localized = $currency->localize($locale_code);

		$this->assertStringSame($expected, $localized->format($number, LocalizedCurrencyFormatter::PATTERN_ACCOUNTING));
	}

	public function provide_test_format_accounting(): array
	{
	    $s1 = Spaces::NARROW_NO_BREAK_SPACE;
	    $s2 = Spaces::NO_BREAK_SPACE;

		return [

			[ 'IEP', 'fr', 123456.789, "123 456,79 £IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123 456,79 €" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123{$s1}456,79{$s2}\$US" ],
			[ 'USD', 'en', 123456.789, '$123,456.79' ],

		];
	}
}
