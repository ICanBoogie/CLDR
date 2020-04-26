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
	const PATTERN_STANDARD = 'standard';
	const PATTERN_ACCOUNTING = 'accounting';

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param int|float $number
	 * @param Currency|string $currency
	 * @param string $pattern
	 * @param array $symbols
	 *
	 * @return string
	 */
	public function __invoke($number, $currency, $pattern = self::PATTERN_STANDARD, array $symbols = [])
	{
		return $this->format($number, $currency, $pattern, $symbols);
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param int|float $number
	 * @param Currency|string $currency
	 * @param string $pattern
	 * @param array $symbols
	 *
	 * @return string
	 */
	public function format($number, $currency, $pattern = self::PATTERN_STANDARD, array $symbols = [])
	{
		$symbols += $this->locale->numbers->symbols + [

			'currencySymbol' => $this->resolve_currency_symbol($currency)

		];

		return $this->target->format($number, $this->resolve_pattern($pattern), $symbols);
	}

	/**
	 * @param Currency|string $currency
	 *
	 * @return string
	 */
	private function resolve_currency_symbol($currency)
	{
		return $this->locale['currencies'][(string) $currency]['symbol'];
	}

	/**
	 * Resolves a pattern.
	 *
	 * The special patterns {@link PATTERN_STANDARD} and {@link PATTERN_ACCOUNTING} are resolved
	 * from the currency formats.
	 *
	 * @param string $pattern
	 *
	 * @return string
	 */
	private function resolve_pattern($pattern)
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
