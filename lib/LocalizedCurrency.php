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
 * Localized currency.
 *
 * @property-read Currency $target
 * @property-read string $name The localized name of the currency.
 * @property-read string $symbol The localized symbol of the currency.
 * @property-read LocalizedCurrencyFormatter $formatter
 */
class LocalizedCurrency extends LocalizedObjectWithFormatter
{
	/**
	 * Returns the formatter to use to format the target object.
	 *
	 * @return LocalizedCurrencyFormatter
	 */
	protected function lazy_get_formatter()
	{
	    return $this->locale->currency_formatter;
	}

	protected function get_name()
	{
		return $this->name_for();
	}

	/**
	 * Returns the localized name of the currency.
	 *
	 * @param int|null $count Used for pluralization.
	 *
	 * @return string
	 */
	public function name_for($count = null)
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

		return $this->locale['currencies'][$this->target->code][$offset];
	}

	/**
	 * @var string
	 */
	private $symbol;

	/**
	 * Returns the localized symbol of the currency.
	 *
	 * @return string
	 */
	protected function get_symbol()
	{
		$symbol = &$this->symbol;

		return $symbol ?: $symbol = $this->locale['currencies'][$this->target->code]['symbol'];
	}

	/**
	 * Formats currency using localized conventions.
	 *
	 * @param number $number
	 * @param string $pattern
	 * @param array $symbols
	 *
	 * @return string
	 */
	public function format($number, $pattern = LocalizedCurrencyFormatter::PATTERN_STANDARD, array $symbols = [])
	{
		return $this->formatter->format($number, $this->target, $pattern, $symbols);
	}
}
