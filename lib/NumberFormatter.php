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

use ICanBoogie\CLDR\Numbers\Symbols;

/**
 * A number formatter.
 */
class NumberFormatter implements Formatter
{
	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param float|int|numeric-string $number
	 *     The number to format.
	 * @param string|NumberPattern $pattern
	 *     The pattern used to format the number.
	 */
	public function __invoke(
		float|int|string $number,
		NumberPattern|string $pattern,
		Symbols $symbols = null,
	): string {
		return $this->format($number, $pattern, $symbols);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param float|int|numeric-string $number
	 *     The number to format.
	 * @param string|NumberPattern $pattern
	 *     The pattern used to format the number.
	 */
	public function format(
		float|int|string $number,
		NumberPattern|string $pattern,
		Symbols $symbols = null,
	): string {
		if (!$pattern instanceof NumberPattern)
		{
			$pattern = NumberPattern::from($pattern);
		}

		$symbols = $symbols ?? Symbols::defaults();

		[ $integer, $decimal ] = $pattern->parse_number($number);

		$formatted_integer = $pattern->format_integer_with_group($integer, $symbols->group);
		$formatted_number = $pattern->format_integer_with_decimal($formatted_integer, $decimal, $symbols->decimal);

		if ($number < 0)
		{
			$number = $pattern->negative_prefix . $formatted_number . $pattern->negative_suffix;
		}
		else
		{
			$number = $pattern->positive_prefix . $formatted_number . $pattern->positive_suffix;
		}

		return strtr($number, [

			'%' => $symbols->percentSign,
			'‰' => $symbols->perMille,

		]);
	}
}
