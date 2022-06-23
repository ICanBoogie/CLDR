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
use function abs;
use function array_key_exists;
use function implode;
use function ltrim;
use function round;
use function str_pad;
use function str_split;
use function strlen;
use function strpos;
use function substr;
use const STR_PAD_LEFT;

/**
 * Representation of a number pattern.
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
final class NumberPattern
{
	/**
	 * @uses get_format
	 */
	use AccessorTrait;

	/**
	 * @var NumberPattern[]
	 */
	static private $instances = [];

	static public function from(string $pattern): NumberPattern
	{
		if (isset(self::$instances[$pattern]))
		{
			return self::$instances[$pattern];
		}

		$format = NumberPatternParser::parse($pattern);

		return self::$instances[$pattern] = new self($pattern, $format);
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
	protected function get_format(): array
	{
		return $this->format;
	}

	private function __construct(string $pattern, array $format)
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

		return $this->accessor_get($property);
	}

	public function __toString(): string
	{
		return $this->pattern;
	}

	/**
	 * Parse a number according to the pattern and return its integer and decimal parts.
	 *
	 * @param int|float $number
	 *
	 * @return array An array made with the integer and decimal parts of the number.
	 */
	public function parse_number($number): array
	{
		$number = abs($number * $this->multiplier);

		if ($this->max_decimal_digits >= 0)
		{
			$number = round($number, $this->max_decimal_digits);
		}

		$number = "$number";
		$pos = strpos($number, '.');

		if ($pos !== false)
		{
			return [ substr($number, 0, $pos), substr($number, $pos + 1) ];
		}

		return [ $number, '' ];
	}

	/**
	 * Formats integer according to group pattern.
	 */
	public function format_integer_with_group(int $integer, string $group_symbol): string
	{
		$integer = str_pad((string) $integer, $this->integer_digits, '0', STR_PAD_LEFT);
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
	 */
	public function format_integer_with_decimal($integer, string $decimal, string $decimal_symbol): string
	{
		if ($decimal === '0') {
			$decimal = '';
		}

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
