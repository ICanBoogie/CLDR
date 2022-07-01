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
final class Currency
{
	public const FRACTION_FALLBACK = 'DEFAULT';

	/**
	 * @uses get_code
	 */
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	/**
	 * @var array<string, string>
	 */
	static private $fraction_mapping = [

		'digits' => '_digits',
		'rounding' => '_rounding',
		'cash_digits' => '_cashDigits',
		'cash_rounding' => '_cashRounding'

	];

	/**
	 * @var array<string, string>
	 */
	private $fraction_data;

	public function __construct(Repository $repository, string $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $property)
	{
		$fraction_mapping = self::$fraction_mapping;

		if (isset($fraction_mapping[$property]))
		{
			$data = $this->resolve_fraction_data();
			$offset = $fraction_mapping[$property];

			return isset($data[$offset]) ? (int) $data[$offset] : null;
		}

		return $this->accessor_get($property);
	}

	/**
	 * Localize the currency.
	 */
	public function localize(string $locale_code): LocalizedCurrency
	{
		return $this->repository->locales[$locale_code]->localize($this);
	}

	/**
	 * @return array<string, string>
	 */
	private function resolve_fraction_data(): array
	{
		$fraction_data = &$this->fraction_data;

		if ($fraction_data)
		{
			return $fraction_data;
		}

		$code = $this->code;
		$fractions = $this->repository->supplemental['currencyData']['fractions'];

		return $fraction_data = $fractions[$code] ?? $fractions[self::FRACTION_FALLBACK];
	}
}
