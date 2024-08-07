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

use ICanBoogie\CLDR\CurrencyFormatter;
use ICanBoogie\CLDR\LocalizedCurrencyFormatter;
use ICanBoogie\CLDR\Spaces;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedCurrencyFormatterTest extends TestCase
{
	use StringHelpers;

	private CurrencyFormatter $formatter;

	protected function setUp(): void
	{
		$this->formatter = new CurrencyFormatter();
	}

	#[DataProvider('provide_test_format')]
	public function test_format(
		string $currency_code,
		string $locale_id,
		float $number,
		string $expected
	): void {
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			locale_for($locale_id),
		);

		$this->assertStringSame($expected, $formatter->format($number, $currency_code));
		$this->assertStringSame($expected, $formatter($number, $currency_code));
	}

	/**
	 * @phpstan-ignore-next-line
	 */
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
	 * @param float|int|numeric-string $number
	 */
	#[DataProvider('provide_test_format_accounting')]
	public function test_format_accounting(
		string $currency_code,
		string $locale_id,
		float|int|string $number,
		string $expected
	): void {
		$formatter = new LocalizedCurrencyFormatter(
			$this->formatter,
			locale_for($locale_id),
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

	/**
	 * @phpstan-ignore-next-line
	 */
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
			locale_for('fr'),
		);

		$this->assertStringSame("€123,5", $formatter(123.45, 'EUR', '¤0.0'));
	}
}
