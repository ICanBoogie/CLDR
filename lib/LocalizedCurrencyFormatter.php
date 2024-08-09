<?php

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
	 * @param string|Currency $currency A {@see Currency} or currency code.
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
		return $this->locale['currencies'][$currency]['symbol'] ?? $currency;
	}

	/**
	 * Resolves a pattern.
	 *
	 * The special patterns {@link PATTERN_STANDARD} and {@link PATTERN_ACCOUNTING} are resolved
	 * from the currency formats.
	 */
	private function resolve_pattern(string $pattern): string
	{
		return match ($pattern) {
			self::PATTERN_STANDARD => $this->locale->numbers->currency_formats['standard'],
			self::PATTERN_ACCOUNTING => $this->locale->numbers->currency_formats['accounting'],
			default => $pattern,
		};
	}
}
