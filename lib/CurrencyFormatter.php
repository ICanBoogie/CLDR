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

use function str_replace;

/**
 * A currency formatter.
 */
final class CurrencyFormatter extends NumberFormatter
{
	/**
	 * @inheritDoc
	 */
	public function format($number, $pattern, array $symbols = []): string
	{
		$symbols += [

			'currencySymbol' => "¤"

		];

		return str_replace("¤", $symbols['currencySymbol'], parent::format($number, $pattern, $symbols));
	}
}
