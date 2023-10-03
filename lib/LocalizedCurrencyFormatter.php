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
 * @extends LocalizedObject<CurrencyFormatter>
 */
class LocalizedCurrencyFormatter extends LocalizedObject implements Formatter
{
	public const PATTERN_STANDARD = 'standard';
	public const PATTERN_ACCOUNTING = 'accounting';

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param float|int|numeric-string $number
	 * @param string|Currency $currency
	 */
	public function __invoke(
		float|int|string $number,
		Currency|string $currency,
		string $pattern = self::PATTERN_STANDARD
	): string {
		return $this->format($number, $currency, $pattern);
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param float|int|numeric-string $number
	 */
	public function format(
		float|int|string $number,
		Currency|string $currency,
		string $pattern = self::PATTERN_STANDARD
	): string {
		return $this->target->format(
			$number,
			$this->resolve_pattern($pattern),
			$this->locale->numbers->symbols,
			$this->resolve_currency_symbol($currency)
		);
	}

	private function resolve_currency_symbol(string $currency): string
	{
		/** @phpstan-ignore-next-line */
		return $this->locale['currencies'][$currency]['symbol'];
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
