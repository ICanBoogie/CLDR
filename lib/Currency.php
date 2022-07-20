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
use ICanBoogie\CLDR\Supplemental\Fraction;

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
 * echo $euro->fraction->code;          // EUR
 * echo $euro->fraction->digits;        // 2
 * echo $euro->fraction->rounding;      // 0
 * echo $euro->fraction->cash_digits;   // 2
 * echo $euro->fraction->cash_rounding; // 0
 * ```
 *
 * @internal
 *
 * @property-read string $code The ISO 4217 code for the currency.
 * @property-read Fraction $fraction
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Supplemental_Currency_Data
 */
final class Currency
{
	/**
	 * @uses get_code
	 * @uses lazy_get_fraction
	 */
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	public function __construct(Repository $repository, string $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	private function lazy_get_fraction(): Fraction
	{
		return $this->repository->supplemental->currency_data->fraction_for($this->code);
	}

	/**
	 * Localizes the currency.
	 */
	public function localize(string $locale_id): LocalizedCurrency
	{
		/** @phpstan-ignore-next-line */
		return $this->repository->locales[$locale_id]->localize($this);
	}
}
