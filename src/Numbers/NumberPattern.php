<?php

namespace ICanBoogie\CLDR\Numbers;

use function abs;
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
    public static function from(string $pattern): NumberPattern
    {
        static $instances;

        return $instances[$pattern] ??= self::do_from($pattern);
    }

    private static function do_from(string $pattern): self
    {
        $parsed_pattern = NumberPatternParser::parse($pattern);

        return new self($pattern, ...$parsed_pattern);
    }

    /**
     * @param string $pattern
     * @param string $positive_prefix
     *     Prefix to a positive number.
     * @param string $positive_suffix
     *     Suffix to a positive number.
     * @param string $negative_prefix
     *     Prefix to a negative number.
     * @param string $negative_suffix
     *     Suffix to negative number.
     * @param int $multiplier
     *     100 for percent, 1000 for per mille.
     * @param int $decimal_digits
     *     The number of required digits after the decimal point.
     *     The string is padded with zeros if there aren't enough digits.
     *     `-1` means the decimal point should be dropped.
     * @param int $max_decimal_digits
     *     The maximum number of digits after the decimal point.
     *     Additional digits will be truncated.
     * @param int $integer_digits
     *     The number of required digits before the decimal point.
     *     The string is padded with zeros if there aren't enough digits.
     * @param int $group_size1
     *     The primary grouping size. `0` means no grouping.
     * @param int $group_size2
     *     The secondary grouping size. `0` means no secondary grouping.
     */
    private function __construct(
        public readonly string $pattern,
        public readonly string $positive_prefix,
        public readonly string $positive_suffix,
        public readonly string $negative_prefix,
        public readonly string $negative_suffix,
        public readonly int $multiplier,
        public readonly int $decimal_digits,
        public readonly int $max_decimal_digits,
        public readonly int $integer_digits,
        public readonly int $group_size1,
        public readonly int $group_size2
    ) {
    }

    public function __toString(): string
    {
        return $this->pattern;
    }

    /**
     * Parses a number according to the pattern and return its integer and decimal parts.
     *
     * @param float|int|numeric-string $number
     *
     * @return array{ 0: int, 1: string}
     *     Where `0` is the integer part and `1` the decimal part.
     */
    public function parse_number(float|int|string $number): array
    {
        $number = abs($number * $this->multiplier);

        if ($this->max_decimal_digits >= 0) {
            $number = round($number, $this->max_decimal_digits);
        }

        $number = "$number";
        $pos = strpos($number, '.');

        if ($pos !== false) {
            return [ (int)substr($number, 0, $pos), substr($number, $pos + 1) ];
        }

        return [ (int)$number, '' ];
    }

    /**
     * Formats an integer according to a group pattern.
     */
    public function format_integer_with_group(int $integer, string $group_symbol): string
    {
        $integer = str_pad((string)$integer, $this->integer_digits, '0', STR_PAD_LEFT);
        $group_size1 = $this->group_size1;

        if ($group_size1 < 1 || strlen($integer) <= $this->group_size1) {
            return $integer;
        }

        $group_size2 = $this->group_size2;

        $str1 = substr($integer, 0, -$group_size1);
        $str2 = substr($integer, -$group_size1);
        $size = $group_size2 > 0 ? $group_size2 : $group_size1;
        $str1 = str_pad($str1, (int)((strlen($str1) + $size - 1) / $size) * $size, ' ', STR_PAD_LEFT);

        return ltrim(implode($group_symbol, str_split($str1, $size))) . $group_symbol . $str2;
    }

    /**
     * Formats an integer with a decimal.
     *
     * @param int|string $integer
     *     An integer, or a formatted integer as returned by {@see format_integer_with_group}.
     */
    public function format_integer_with_decimal(int|string $integer, string $decimal, string $decimal_symbol): string
    {
        if ($decimal === '0') {
            $decimal = '';
        }

        if ($this->decimal_digits > strlen($decimal)) {
            $decimal = str_pad($decimal, $this->decimal_digits, '0');
        }

        if (strlen($decimal)) {
            $decimal = $decimal_symbol . $decimal;
        }

        return "$integer" . $decimal;
    }
}
