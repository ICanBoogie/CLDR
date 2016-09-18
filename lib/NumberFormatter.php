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
 * Formats numbers.
 *
 * @method LocalizedNumberFormatter localize($locale_code)
 */
class NumberFormatter implements Formatter
{
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use LocalizeTrait;

	static private $default_symbols = [

		'decimal' => ".",
		'group' => ",",
		'percentSign' => "%",
		'plusSign' => "+",
		'minusSign' => "-",
		'perMille' => "‰"

	];

	/**
	 * Returns the precision of a number.
	 *
	 * @param number $number
	 *
	 * @return int
	 *
	 * @deprecated
	 *
	 * @see Number::precision_from()
	 */
	static public function precision_from($number)
	{
		return Number::precision_from($number);
	}

	/**
	 * Returns a number rounded to the specified precision.
	 *
	 * @param number $number
	 * @param int $precision
	 *
	 * @return float
	 *
	 * @deprecated
	 *
	 * @see Number::round_to()
	 */
	static public function round_to($number, $precision)
	{
		return Number::round_to($number, $precision);
	}

	/**
	 * Parses a number.
	 *
	 * @param number $number
	 * @param null|int $precision
	 *
	 * @return array
	 *
	 * @deprecated
	 *
	 * @see Number::parse()
	 */
	static public function parse_number($number, $precision = null)
	{
		return Number::parse($number, $precision);
	}

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository=null)
	{
		$this->repository = $repository;
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param mixed $number The number to be formatted.
	 * @param string $pattern The pattern used to format the number.
	 * @param array $symbols Symbols.
	 *
	 * @return string The formatted number.
	 */
	public function __invoke($number, $pattern, array $symbols = [])
	{
		return $this->format($number, $pattern, $symbols);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param mixed $number The number to be formatted.
	 * @param string $pattern The pattern used to format the number.
	 * @param array $symbols Symbols.
	 *
	 * @return string The formatted number.
	 */
	public function format($number, $pattern, array $symbols = [])
	{
		if (!($pattern instanceof NumberPattern))
		{
			$pattern = NumberPattern::from($pattern);
		}

		$symbols += self::$default_symbols;

		list($integer, $decimal) = $pattern->parse_number($number);

		$formatted_integer = $pattern->format_integer_with_group($integer, $symbols['group']);
		$formatted_number = $pattern->format_integer_with_decimal($formatted_integer, $decimal, $symbols['decimal']);

		if ($number < 0)
		{
			$number = $pattern->negative_prefix . $formatted_number . $pattern->negative_suffix;
		}
		else
		{
			$number = $pattern->positive_prefix . $formatted_number . $pattern->positive_suffix;
		}

		return strtr($number, [

			'%' => $symbols['percentSign'],
			'‰' => $symbols['perMille']

		]);
	}
}
