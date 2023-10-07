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

final class LocalizedCurrencyFormatterTest extends TestCase
{
	use StringHelpers;

	private CurrencyFormatter $formatter;
	private Repository $repository;

	protected function setUp(): void
	{
		$this->repository = get_repository();
		$this->formatter = new CurrencyFormatter();
	}

	#[DataProvider('provide_test_format')]
	public function test_format(
		string $currency_code,
		string $locale_code,
		float $number,
		string $expected
	): void {
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales[$locale_code]
		);

		$this->assertStringSame($expected, $formatter->format($number, $currency_code));
		$this->assertStringSame($expected, $formatter($number, $currency_code));
	}

	public static function provide_test_format(): array
	{
		$s1 = Spaces::NARROW_NO_BREAK_SPACE;
		$s2 = Spaces::NO_BREAK_SPACE;

		return [

			[ 'IEP', 'fr', 123456.789, "123{$s1}456,79{$s2}£IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123{$s1}456,79{$s2}€" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123{$s1}456,79{$s2}\$US" ],
			[ 'USD', 'en', 123456.789, "\$123,456.79" ],

		];
	}

	/**
	 * @dataProvider provide_test_format_accounting
	 *
	 * @param numeric $number
	 */
	public function test_format_accounting(string $currency_code, string $locale_code, $number, string $expected): void
	{
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales[$locale_code]
		);

		$this->assertStringSame(
			$expected,
			$formatter->format($number, $currency_code, $formatter::PATTERN_ACCOUNTING)
		);

		$this->assertStringSame(
			$expected,
			$formatter($number, $currency_code, $formatter::PATTERN_ACCOUNTING)
		);
	}

	public static function provide_test_format_accounting(): array
	{
		$s1 = Spaces::NARROW_NO_BREAK_SPACE;
		$s2 = Spaces::NO_BREAK_SPACE;

		return [

			[ 'IEP', 'fr', 123456.789, "123{$s1}456,79{$s2}£IE" ],
			[ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
			[ 'EUR', 'fr', 123456.789, "123{$s1}456,79{$s2}€" ],
			[ 'EUR', 'en', 123456.789, "€123,456.79" ],
			[ 'USD', 'fr', 123456.789, "123{$s1}456,79{$s2}\$US" ],
			[ 'USD', 'en', 123456.789, "\$123,456.79" ],

		];
	}

	public function test_should_format_with_custom_pattern(): void
	{
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			$this->repository->locales['fr']
		);

		$this->assertStringSame("€123,5", $formatter(123.45, 'EUR', '¤0.0'));
	}
}
