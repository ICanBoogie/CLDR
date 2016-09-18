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

class Number
{
	/**
	 * Returns the precision of a number.
	 *
	 * @param number $number
	 *
	 * @return int
	 */
	static public function precision_from($number)
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
	 * @param number $number
	 * @param int $precision
	 *
	 * @return float
	 */
	static public function round_to($number, $precision)
	{
		return round($number, $precision);
	}

	/**
	 * Parses a number.
	 *
	 * @param number $number
	 * @param null|int $precision
	 *
	 * @return array
	 */
	static public function parse($number, $precision = null)
	{
		if ($precision === null)
		{
			$precision = self::precision_from($number);
		}

		$number = self::round_to($number, $precision);
		$number = abs($number);
		$number = number_format($number, $precision, '.', '');

		return explode('.', (string) $number);
	}
}
