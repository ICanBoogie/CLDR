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
 */
final class NumberPattern
{
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

		$parsed_pattern = NumberPatternParser::parse($pattern);

		return self::$instances[$pattern] = new self(
			$pattern,
			$parsed_pattern['positive_prefix'],
			$parsed_pattern['positive_suffix'],
			$parsed_pattern['negative_prefix'],
			$parsed_pattern['negative_suffix'],
			$parsed_pattern['multiplier'],
			$parsed_pattern['decimal_digits'],
			$parsed_pattern['max_decimal_digits'],
			$parsed_pattern['integer_digits'],
			$parsed_pattern['group_size1'],
			$parsed_pattern['group_size2']
		);
	}

	/**
	 * @var string
	 */
	private $pattern;

	/**
	 * Prefix to positive number.
	 *
	 * @var string
	 * @readonly
	 */
	public $positive_prefix;

	/**
	 * Suffix to positive number.
	 *
	 * @var string
	 * @readonly
	 */
	public $positive_suffix;

	/**
	 * Prefix to negative number.
	 *
	 * @var string
	 * @readonly
	 */
	public $negative_prefix;

	/**
	 * Suffix to negative number.
	 *
	 * @var string
	 * @readonly
	 */
	public $negative_suffix;

	/**
	 * 100 for percent, 1000 for per mille.
	 *
	 * @var int
	 * @readonly
	 */
	public $multiplier;

	/**
	 * The number of required digits after decimal point. The string is padded with zeros if there is not enough digits.
	 * `-1` means the decimal point should be dropped.
	 *
	 * @var int
	 * @readonly
	 */
	public $decimal_digits;

	/**
	 * The maximum number of digits after decimal point. Additional digits will be truncated.
	 *
	 * @var int
	 * @readonly
	 */
	public $max_decimal_digits;

	/**
	 * The number of required digits before decimal point. The string is padded with zeros if there is not enough
	 * digits.
	 *
	 * @var int
	 * @readonly
	 */
	public $integer_digits;

	/**
	 * The primary grouping size. `0` means no grouping.
	 *
	 * @var int
	 * @readonly
	 */
	public $group_size1;

	/**
	 * The secondary grouping size. `0` means no secondary grouping.
	 *
	 * @var int
	 * @readonly
	 */
	public $group_size2;

	private function __construct(
		string $pattern,
		string $positive_prefix,
	    string $positive_suffix,
	    string $negative_prefix,
	    string $negative_suffix,
	    int $multiplier,
	    int $decimal_digits,
	    int $max_decimal_digits,
	    int $integer_digits,
	    int $group_size1,
	    int $group_size2
	) {
		$this->pattern = $pattern;
		$this->positive_prefix = $positive_prefix;
	    $this->positive_suffix = $positive_suffix;
	    $this->negative_prefix = $negative_prefix;
	    $this->negative_suffix = $negative_suffix;
	    $this->multiplier = $multiplier;
	    $this->decimal_digits = $decimal_digits;
	    $this->max_decimal_digits = $max_decimal_digits;
	    $this->integer_digits = $integer_digits;
	    $this->group_size1 = $group_size1;
	    $this->group_size2 = $group_size2;
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
	 * @return array{ 0: int, 1: string}
	 *     Where `0` is the integer part and `1` the decimal part.
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
			return [ (int) substr($number, 0, $pos), substr($number, $pos + 1) ];
		}

		return [ (int) $number, '' ];
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
