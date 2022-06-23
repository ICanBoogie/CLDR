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

use function strlen;
use function strrpos;

final class Number
{
	/**
	 * Returns the precision of a number.
	 *
	 * @param numeric $number
	 */
	static public function precision_from($number): int
	{
		$number = (string) $number;
		$pos = strrpos($number, '.');

		if (!$pos)
		{
			return 0;
		}

		return strlen($number) - $pos - 1;
	}

	/**
	 * Returns a number rounded to the specified precision.
	 *
	 * @param numeric $number
	 */
	static public function round_to($number, int $precision): float
	{
		return round($number, $precision);
	}

	/**
	 * Parses a number.
	 *
	 * @param numeric $number
	 * @param null|int $precision
	 *
	 * @return array An array of [ $integer, $fractional ]. The fractional part is `null` if
	 * `$number` has no decimal separator.
	 */
	static public function parse($number, int $precision = null): array
	{
		if ($precision === null)
		{
			$precision = self::precision_from($number);
		}

		$number = self::round_to($number, $precision);
		$number = abs($number);
		$number = number_format($number, $precision, '.', '');

		[ $integer, $fractional ] = explode('.', (string) $number) + [ 1 => null ];

		return [ (int) $integer, $fractional ];
	}
}
