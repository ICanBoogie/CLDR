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
 *
 * @package ICanBoogie\CLDR
 */
class CurrencyFormatter extends NumberFormatter
{
	protected $localized_currency;

	public function __construct(Numbers $numbers, LocalizedCurrency $localized_currency)
	{
		$this->localized_currency = $localized_currency;

		parent::__construct($numbers);
	}

	public function format($number, $pattern, array $options=[])
	{
		return parent::format($number, $pattern, $options + [

			'currency_symbol' => $this->localized_currency->symbol

		]);
	}
}
