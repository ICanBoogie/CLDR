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
 * @property-read CurrencyFormatter $formatter
 */
class LocalizedCurrency extends LocalizedObjectWithFormatter
{
	const PATTERN_STANDARD = 'standard';
	const PATTERN_ACCOUNTING = 'accounting';

	/**
	 * Returns the formatter to use to format the target object.
	 *
	 * @return CurrencyFormatter
	 */
	protected function lazy_get_formatter()
	{
		return new CurrencyFormatter($this->locale->repository);
	}

	/**
	 * Returns the localized name of the currency.
	 *
	 * @param int|null $count Used for pluralization.
	 *
	 * @return string
	 */
	public function get_name($count = null)
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
	private $_symbol;

	/**
	 * Returns the localized symbol of the currency.
	 *
	 * @return string
	 */
	protected function get_symbol()
	{
		$symbol = &$this->_symbol;

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
	public function format($number, $pattern = self::PATTERN_STANDARD, array $symbols = [])
	{
		$symbols += $this->locale->numbers->symbols + [

			'currencySymbol' => $this->symbol

		];

		return $this->formatter->format($number, $this->resolve_pattern($pattern), $symbols);
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
	protected function resolve_pattern($pattern)
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
