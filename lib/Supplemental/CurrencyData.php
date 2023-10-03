<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Supplemental;

/**
 * @internal
 *
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Supplemental_Currency_Data
 */
final class CurrencyData
{
	private const FRACTION_FALLBACK = 'DEFAULT';

	/**
	 * @param array{ fractions: array, region: array } $data
	 *
	 * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-core/supplemental/currencyData.json
	 *
	 * @phpstan-ignore-next-line
	 */
	public function __construct(
		private readonly array $data
	) {
	}

	/**
	 * @var array<string, Fraction>
	 */
	private array $fractions;

	public function fraction_for(string $currency_code): Fraction
	{
		return $this->fractions[$currency_code] ??= $this->build_fraction($currency_code);
	}

	private Fraction $default_fraction;

	private function build_fraction(string $currency_code): Fraction
	{
		$data = $this->data['fractions'][$currency_code] ?? null;

		if (!$data)
		{
			return $this->default_fraction ??= $this->build_fraction(self::FRACTION_FALLBACK);
		}

		return Fraction::from($data);
	}
}
