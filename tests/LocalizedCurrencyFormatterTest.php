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

class LocalizedCurrencyFormatterTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var string
	 */
	private $currency;

	/**
	 * @var CurrencyFormatter
	 */
	private $formatter;

	/**
	 * @var Repository
	 */
	private $repository;

	public function setUp()
	{
		$this->currency = 'IEP';
		$this->repository = get_repository();
		$this->formatter = new CurrencyFormatter($this->repository);
	}

	/**
	 * @dataProvider provide_test_format
	 *
	 * @param string $currency_code
	 * @param string $locale_code
	 * @param int $number
	 * @param string $expected
	 */
	public function test_format($currency_code, $locale_code, $number, $expected)
	{
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales[$locale_code]
		);

		$this->assertSame($expected, $formatter->format($number, $currency_code));
		$this->assertSame($expected, $formatter($number, $currency_code));
	}

	public function provide_test_format()
	{
		return [

			[ 'IEP', 'fr', 123456.789, "123 456,79 £IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123 456,79 €" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123 456,79 \$US" ],
			[ 'USD', 'en', 123456.789, "\$123,456.79" ],

		];
	}

	/**
	 * @dataProvider provide_test_format_accounting
	 *
	 * @param string $currency_code
	 * @param string $locale_code
	 * @param int $number
	 * @param string $expected
	 */
	public function test_format_accounting($currency_code, $locale_code, $number, $expected)
	{
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales[$locale_code]
		);

		$this->assertSame(
			$expected,
			$formatter->format($number, $currency_code, $formatter::PATTERN_ACCOUNTING)
		);

		$this->assertSame(
			$expected,
			$formatter($number, $currency_code, $formatter::PATTERN_ACCOUNTING)
		);
	}

	public function provide_test_format_accounting()
	{
		return [

			[ 'IEP', 'fr', 123456.789, "123 456,79 £IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123 456,79 €" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123 456,79 \$US" ],
			[ 'USD', 'en', 123456.789, "\$123,456.79" ],

		];
	}

	public function test_should_format_with_custom_pattern()
	{
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales['fr']
		);

		$this->assertSame("€123,5", $formatter(123.45, 'EUR', '¤0.0'));
	}
}
