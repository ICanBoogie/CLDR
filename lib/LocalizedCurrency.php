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
 * A localized currency.
 *
 * @extends LocalizedObjectWithFormatter<Currency, LocalizedCurrencyFormatter>
 *
 * @property-read string $name The localized name of the currency.
 * @property-read string $symbol The localized symbol of the currency.
 */
class LocalizedCurrency extends LocalizedObjectWithFormatter
{
	/**
	 * @return LocalizedCurrencyFormatter
	 */
	protected function lazy_get_formatter(): Formatter
	{
	    return $this->locale->currency_formatter;
	}

	/**
	 * @uses get_name
	 */
	protected function get_name(): string
	{
		return $this->name_for();
	}

	/**
	 * Returns the localized name of the currency.
	 *
	 * @param int|null $count Used for pluralization.
	 */
	public function name_for(int $count = null): string
	{
		$offset = 'displayName';

		if ($count == 1)
		{
			$offset .= '-count-one';
		}
		else if ($count)
		{
			$offset .= '-count-other';
		}

		/** @phpstan-ignore-next-line */
		return $this->locale['currencies'][$this->target->code][$offset];
	}

	/**
	 * @var string|null
	 */
	private $symbol;

	/**
	 * Returns the localized symbol of the currency.
	 *
	 * @uses get_symbol
	 */
	protected function get_symbol(): string
	{
		return $this->symbol
			?? $this->symbol = $this->locale['currencies'][$this->target->code]['symbol']; // @phpstan-ignore-line
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param float|int $number
	 */
	public function format($number, string $pattern = LocalizedCurrencyFormatter::PATTERN_STANDARD): string
	{
		return $this->formatter->format($number, $this->target, $pattern);
	}
}
