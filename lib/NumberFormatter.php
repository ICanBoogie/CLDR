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

	/**
	 * Return the precision of a number.
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
	public function format($number, $pattern, array $symbols=[])
	{
		if (!($pattern instanceof NumberPattern))
		{
			$pattern = NumberPattern::from($pattern);
		}

		$symbols += [

			'decimal' => ".",
			'group' => ",",
			'percentSign' => "%",
			'plusSign' => "+",
			'minusSign' => "-",
			'perMille' => "‰"

		];

		#

		$negative = $number < 0;
		$number = abs($number * $pattern->multiplier);

		if ($pattern->max_decimal_digits >= 0)
		{
			$number = round($number, $pattern->max_decimal_digits);
		}

		$number = "$number";

		if (($pos = strpos($number, '.')) !== false)
		{
			$integer = substr($number, 0, $pos);
			$decimal = substr($number, $pos + 1);
		}
		else
		{
			$integer = $number;
			$decimal = '';
		}

		$integer = str_pad($integer, $pattern->integer_digits, '0', STR_PAD_LEFT);
		$group_size1 = $pattern->group_size1;

		if ($group_size1 > 0 && strlen($integer) > $pattern->group_size1)
		{
			$group_size2 = $pattern->group_size2;

			$str1 = substr($integer, 0, -$group_size1);
			$str2 = substr($integer, -$group_size1);
			$size = $group_size2 > 0 ? $group_size2 : $group_size1;
			$str1 = str_pad($str1, (int) ((strlen($str1) + $size - 1) / $size) * $size, ' ', STR_PAD_LEFT);
			$integer = ltrim(implode($symbols['group'], str_split($str1, $size))) . $symbols['group'] . $str2;
		}

		if ($pattern->decimal_digits > strlen($decimal))
		{
			$decimal = str_pad($decimal, $pattern->decimal_digits, '0');
		}

		if (strlen($decimal))
		{
			$decimal = $symbols['decimal'] . $decimal;
		}

		$number = $integer . $decimal;

		if ($negative)
		{
			$number = $pattern->negative_prefix . $number . $pattern->negative_suffix;
		}
		else
		{
			$number = $pattern->positive_prefix . $number . $pattern->positive_suffix;
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
