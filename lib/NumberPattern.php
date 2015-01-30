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
 * Representation of a number pattern.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read array $format
 * @property-read string $positive_prefix Prefix to positive number.
 * @property-read string $positive_suffix Suffix to positive number.
 * @property-read string $negative_prefix Prefix to negative number.
 * @property-read string $negative_suffix Suffix to negative number.
 * @property-read int $multiplier 100 for percent, 1000 for per mille.
 * @property-read int $decimal_digits The number of required digits after decimal point. The
 * string is padded with zeros if there is not enough digits. `-1` means the decimal point should
 * be dropped.
 * @property-read int $max_decimal_digits The maximum number of digits after decimal point.
 * Additional digits will be truncated.
 * @property-read int $integer_digits The number of required digits before decimal point. The
 * string is padded with zeros if there is not enough digits.
 * @property-read int $group_size1 The primary grouping size. `0` means no grouping.
 * @property-read int $group_size2 The secondary grouping size. `0` means no secondary grouping
 */
class NumberPattern
{
	use AccessorTrait;

	static private $instances = [];

	/**
	 * @param $pattern
	 *
	 * @return NumberPattern
	 */
	static public function from($pattern)
	{
		if (isset(self::$instances[$pattern]))
		{
			return self::$instances[$pattern];
		}

		$format = self::parse($pattern);

		return self::$instances[$pattern] = new static($pattern, $format);
	}

	/**
	 * Parses a given string pattern.
	 *
	 * @param string $pattern The pattern to be parsed.
	 *
	 * @return array The parsed pattern.
	 */
	static protected function parse($pattern)
	{
		$format = [

			'positive_prefix' => '',
			'positive_suffix' => '',
			'negative_prefix' => '',
			'negative_suffix' => '',
			'multiplier' => 1,
			'decimal_digits' => 0,
			'max_decimal_digits' => 0,
			'integer_digits' => 0,
			'group_size1' => 0,
			'group_size2' => 0

		];

		#
		# Positive and negative patterns
		#

		$patterns = explode(';', $pattern);

		if (preg_match('/^(.*?)[#,\.0]+(.*?)$/', $patterns[0], $matches))
		{
			$format['positive_prefix'] = $matches[1];
			$format['positive_suffix'] = $matches[2];
		}

		if (isset($patterns[1]) && preg_match('/^(.*?)[#,\.0]+(.*?)$/', $patterns[1], $matches))
		{
			$format['negative_prefix'] = $matches[1];
			$format['negative_suffix'] = $matches[2];
		}
		else
		{
			$format['negative_prefix'] = '-' . $format['positive_prefix'];
			$format['negative_suffix'] = $format['positive_suffix'];
		}

		$pat = $patterns[0];

		#
		# Multiplier
		#

		if (strpos($pat, '%') !== false)
		{
			$format['multiplier'] = 100;
		}
		else if (strpos($pat, '‰') !== false)
		{
			$format['multiplier'] = 1000;
		}

		#
		# Decimal part
		#

		$pos = strpos($pat,'.');

		if ($pos !== false)
		{
			$pos2 = strrpos($pat, '0');
			$format['decimal_digits'] = $pos2 > $pos
				? $pos2 - $pos
				: 0;

			$pos3 = strrpos($pat, '#');
			$format['max_decimal_digits'] = $pos3 >= $pos2
				? $pos3 - $pos
				: $format['decimal_digits'];

			$pat = substr($pat, 0, $pos);
		}

		#
		# Integer part
		#

		$p = str_replace(',', '', $pat);
		$pos = strpos($p, '0');

		$format['integer_digits'] = $pos !== false
			? strrpos($p, '0') - $pos + 1
			: 0;

		#
		# Group sizes
		#

		$p = str_replace('#', '0', $pat);
		$pos = strrpos($pat, ',');

		if ($pos !== false)
		{
			$format['group_size1'] = strrpos($p, '0') - $pos;

			$pos2 = strrpos(substr($p, 0, $pos), ',');
			$format['group_size2'] = $pos2 !== false
				? $pos - $pos2 - 1
				: 0;
		}

		return $format;
	}

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * @var array
	 */
	private $format;

	/**
	 * @return array
	 */
	protected function get_format()
	{
		return $this->format;
	}

	public function __construct($pattern, array $format)
	{
		$this->pattern = $pattern;
		$this->format = $format;
	}

	public function __get($property)
	{
		if (array_key_exists($property, $this->format))
		{
			return $this->format[$property];
		}

		return $this->__object_get($property);
	}

	public function __toString()
	{
		return $this->pattern;
	}

	/**
	 * Parse a number according to the pattern and return its integer and decimal parts.
	 *
	 * @param number $number
	 *
	 * @return array An array made with the integer and decimal parts of the number.
	 */
	public function parse_number($number)
	{
		$number = abs($number * $this->multiplier);

		if ($this->max_decimal_digits >= 0)
		{
			$number = round($number, $this->max_decimal_digits);
		}

		$number = "$number";

		if (($pos = strpos($number, '.')) !== false)
		{
			return [ substr($number, 0, $pos), substr($number, $pos + 1) ];
		}

		return [ $number, '' ];
	}

	/**
	 * Formats integer according to group pattern.
	 *
	 * @param int $integer
	 * @param string $group_symbol
	 *
	 * @return string
	 */
	public function format_integer_with_group($integer, $group_symbol)
	{
		$integer = str_pad($integer, $this->integer_digits, '0', STR_PAD_LEFT);
		$group_size1 = $this->group_size1;

		if ($group_size1 < 1 || strlen($integer) <= $this->group_size1)
		{
			return $integer;
		}

		$group_size2 = $this->group_size2;

		$str1 = substr($integer, 0, -$group_size1);
		$str2 = substr($integer, -$group_size1);
		$size = $group_size2 > 0 ? $group_size2 : $group_size1;
		$str1 = str_pad($str1, (int) ((strlen($str1) + $size - 1) / $size) * $size, ' ', STR_PAD_LEFT);

		return ltrim(implode($group_symbol, str_split($str1, $size))) . $group_symbol . $str2;
	}

	/**
	 * Formats an integer with a decimal.
	 *
	 * @param string|int $integer An integer, or a formatted integer as returned by
	 * {@link format_integer_with_group}.
	 * @param string $decimal
	 * @param string $decimal_symbol
	 *
	 * @return string
	 */
	public function format_integer_with_decimal($integer, $decimal, $decimal_symbol)
	{
		if ($this->decimal_digits > strlen($decimal))
		{
			$decimal = str_pad($decimal, $this->decimal_digits, '0');
		}

		if (strlen($decimal))
		{
			$decimal = $decimal_symbol . $decimal;
		}

		return "$integer" . $decimal;
	}
}
