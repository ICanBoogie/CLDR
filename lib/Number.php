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

use function assert;
use function is_numeric;
use function is_string;
use function preg_match;
use function str_repeat;
use function strlen;
use function strpos;
use function strrpos;
use function substr;

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
	 * @param float|int $number
	 */
	static public function round_to($number, int $precision): float
	{
		return round($number, $precision);
	}

	/**
	 * Parses a number.
	 *
	 * @param numeric $number
	 *
	 * @return array{ 0: int, 1: string|null}
	 *     Where `0` is the integer part and `1` the fractional part. The fractional part is `null` if
	 *     `$number` has no decimal separator. The fractional part is returned as a string to preserve '03' from
	 *     '1.03'.
	 */
	static public function parse($number, int $precision = null): array
	{
		if ($precision === null)
		{
			$precision = self::precision_from($number);
		}

		$number = self::round_to($number+0, $precision);
		$number = abs($number);
		$number = number_format($number, $precision, '.', '');

		[ $integer, $fractional ] = explode('.', (string) $number) + [ 1 => null ];

		return [ (int) $integer, $fractional ];
	}

	/**
	 * @param numeric $number
	 * @param int|null $c
	 *
	 * @return numeric
	 */
	static public function expand_compact_decimal_exponent($number, int &$c = null)
	{
		$c = 0;

		if (!is_string($number))
		{
			return $number;
		}

		$c_pos = strpos($number, 'c');

		if ($c_pos === false)
		{
			return $number;
		}

		$c = (int) substr($number, $c_pos + 1);
		$number = substr($number, 0, $c_pos);
		preg_match('/0+$/', $number, $match);
		assert(is_numeric($number));
		$multiplier = (int) ('1' . str_repeat('0', $c));
		$number *= $multiplier;

		if ($match) {
			return $number . $match[0]; // @phpstan-ignore-line
		}

		return $number;
	}
}
