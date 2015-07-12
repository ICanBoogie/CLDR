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

use ICanBoogie\Accessor\AccessorTrait;

/**
 * A currency.
 *
 * ```php
 * <?php
 *
 * use ICanBoogie\CLDR\Currency;
 *
 * $euro = new Currency($cldr, 'EUR');
 * # or
 * $euro = $cldr->currencies['EUR'];
 *
 * echo $euro->code;        // EUR
 * echo $euro->digits;      // 2
 * echo $euro->rounding;    // 0
 * echo $euro->cash_digits;   //
 * ```
 *
 * @property-read string $code The ISO 4217 code for the currency.
 * @property-read int $digits The minimum and maximum number of decimal digits normally formatted.
 * @property-read int $rounding The rounding increment, in units of 10^-digits.
 * @property-read int $cash_digits The number of decimal digits to be used when formatting quantities used in cash transactions.
 * @property-read int $cash_rounding The cash rounding increment, in units of 10^cashDigits
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Supplemental_Currency_Data
 */
class Currency
{
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	/**
	 * @param Repository $repository
	 * @param string $code Currency ISO code.
	 */
	public function __construct(Repository $repository, $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	public function __get($property)
	{
		if (in_array($property, [ 'digits', 'rounding', 'cash_digits', 'cash_rounding' ]))
		{
			$data = $this->repository->supplemental['currencyData'][$this->code];
			$offset = '_' . $property;

			return isset($data[$offset]) ? (int) $data[$offset] : null;
		}

		return $this->accessor_get($property);
	}

	/**
	 * Localize the currency.Doc
	 *
	 * @param $locale_code
	 *
	 * @return LocalizedCurrency
	 */
	public function localize($locale_code)
	{
		return $this->repository->locales[$locale_code]->localize($this);
	}
}
