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
 * A currency formatter.
 */
class CurrencyFormatter extends NumberFormatter
{
	/**
	 * @inheritdoc
	 */
	public function format($number, $pattern, array $symbols = [])
	{
		$symbols += [

			'currencySymbol' => "¤"

		];

		return str_replace("¤", $symbols['currencySymbol'], parent::format($number, $pattern, $symbols));
	}
}
