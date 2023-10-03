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

use ICanBoogie\CLDR\Numbers\Symbols;

use function str_replace;

/**
 * A currency formatter.
 */
final class CurrencyFormatter extends NumberFormatter
{
	public const DEFAULT_CURRENCY_SYMBOL = '¤';

	/**
	 * @inheritDoc
	 */
	public function format(
		float|int|string $number,
		NumberPattern|string $pattern,
		Symbols $symbols = null,
		string $currencySymbol = self::DEFAULT_CURRENCY_SYMBOL
	): string {
		return str_replace(
			self::DEFAULT_CURRENCY_SYMBOL,
			$currencySymbol,
			parent::format($number, $pattern, $symbols)
		);
	}
}
