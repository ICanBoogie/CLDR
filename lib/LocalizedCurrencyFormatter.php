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

/**
 * Formats currencies using locale conventions.
 *
 * @property-read CurrencyFormatter $target
 */
class LocalizedCurrencyFormatter extends LocalizedObject implements Formatter
{
	public const PATTERN_STANDARD = 'standard';
	public const PATTERN_ACCOUNTING = 'accounting';

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param numeric $number
	 * @param Currency|string $currency
	 */
	public function __invoke($number, $currency, string $pattern = self::PATTERN_STANDARD, array $symbols = []): string
	{
		return $this->format($number, $currency, $pattern, $symbols);
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param numeric $number
	 * @param Currency|string $currency
	 */
	public function format($number, $currency, string $pattern = self::PATTERN_STANDARD, array $symbols = []): string
	{
		$symbols += $this->locale->numbers->symbols + [

			'currencySymbol' => $this->resolve_currency_symbol($currency)

		];

		return $this->target->format($number, $this->resolve_pattern($pattern), $symbols);
	}

	/**
	 * @param Currency|string $currency
	 */
	private function resolve_currency_symbol($currency): string
	{
		return $this->locale['currencies'][(string) $currency]['symbol'];
	}

	/**
	 * Resolves a pattern.
	 *
	 * The special patterns {@link PATTERN_STANDARD} and {@link PATTERN_ACCOUNTING} are resolved
	 * from the currency formats.
	 */
	private function resolve_pattern(string $pattern): string
	{
		switch ($pattern)
		{
			case self::PATTERN_STANDARD:

				return $this->locale->numbers->currency_formats['standard'];

			case self::PATTERN_ACCOUNTING:

				return $this->locale->numbers->currency_formats['accounting'];
		}

		return $pattern;
	}
}
