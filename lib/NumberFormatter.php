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

/**
 * Formats numbers.
 *
 * @package ICanBoogie\CLDR
 */
class NumberFormatter
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

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
	 * @param $number
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
	static public function parse_number($number, $precision=null)
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

	/**
	 * Localize the instance.
	 *
	 * @param $locale_code
	 *
	 * @return LocalizedNumberFormatter
	 *
	 * @throw \LogicException when the instance was created without a repository.
	 */
	public function localize($locale_code)
	{
		if (!$this->repository)
		{
			throw new \LogicException("The instance was created without a repository.");
		}

		return $this->repository->locales[$locale_code]->localize($this);
	}
}
