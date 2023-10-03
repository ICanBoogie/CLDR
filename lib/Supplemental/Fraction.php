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
final class Fraction
{
	/**
	 * @param array{ _digits?: string, _rounding?: string, _cashDigits?: string, _cashRounding?: string } $data
	 */
	static public function from(array $data): self
	{
		$digits = (int) ($data['_digits'] ?? 2);
		$rounding = (int) ($data['_rounding'] ?? 0);

		return new self(
			$digits,
			$rounding,
			(int) ($data['_cashDigits'] ?? $digits),
			(int) ($data['_cashRounding'] ?? $rounding)
		);
	}

	/**
	 * @param int $digits
	 *     The minimum and maximum number of decimal digits normally formatted.
	 * @param int $rounding
	 *     The rounding increment, in units of 10^-digits.
	 * @param int $cash_digits
	 *     The number of decimal digits to be used when formatting quantities used in cash transactions.
	 * @param int $cash_rounding
	 *     The cash rounding increment, in units of 10^cashDigits.
	 */
	private function __construct(
		public readonly int $digits,
		public readonly int $rounding,
		public readonly int $cash_digits,
		public readonly int $cash_rounding
	) {
	}
}
