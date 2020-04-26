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

use function explode;
use function preg_match;
use function str_replace;
use function strpos;
use function strrpos;
use function substr;

/**
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Number_Pattern_Character_Definitions
 */
final class NumberPatternParser
{
	public const PATTERN_REGEX = '/^(.*?)[#,\.0]+(.*?)$/';

	static private $initial_format = [

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

	/**
	 * Parses a given string pattern.
	 */
	static public function parse(string $pattern): array
	{
		$format = self::$initial_format;

		self::parse_multiple_patterns($pattern, $format);
		self::parse_multiplier($pattern, $format);
		self::parse_decimal_part($pattern, $format);
		self::parse_integer_part($pattern, $format);
		self::parse_group_sizes($pattern, $format);

		return $format;
	}

	static private function parse_multiple_patterns(string &$pattern, array &$format): void
	{
		$patterns = explode(';', $pattern);

		if (preg_match(self::PATTERN_REGEX, $patterns[0], $matches))
		{
			$format['positive_prefix'] = $matches[1];
			$format['positive_suffix'] = $matches[2];
		}

		if (isset($patterns[1]) && preg_match(self::PATTERN_REGEX, $patterns[1], $matches))
		{
			$format['negative_prefix'] = $matches[1];
			$format['negative_suffix'] = $matches[2];
		}
		else
		{
			$format['negative_prefix'] = '-' . $format['positive_prefix'];
			$format['negative_suffix'] = $format['positive_suffix'];
		}

		$pattern = $patterns[0];
	}

	static private function parse_multiplier(string $pattern, array &$format): void
	{
		if (strpos($pattern, '%') !== false)
		{
			$format['multiplier'] = 100;
		}
		else
		{
			if (strpos($pattern, '‰') !== false)
			{
				$format['multiplier'] = 1000;
			}
		}
	}

	static private function parse_decimal_part(string &$pattern, array &$format): void
	{
		$pos = strpos($pattern, '.');

		if ($pos !== false)
		{
			$pos2 = strrpos($pattern, '0');
			$format['decimal_digits'] = $pos2 > $pos
				? $pos2 - $pos
				: 0;

			$pos3 = strrpos($pattern, '#');
			$format['max_decimal_digits'] = $pos3 >= $pos2
				? $pos3 - $pos
				: $format['decimal_digits'];

			$pattern = substr($pattern, 0, $pos);
		}

	}

	static private function parse_integer_part(string $pattern, array &$format): void
	{
		$p = str_replace(',', '', $pattern);
		$pos = strpos($p, '0');

		$format['integer_digits'] = $pos !== false
			? strrpos($p, '0') - $pos + 1
			: 0;
	}

	static private function parse_group_sizes(string $pattern, array &$format): void
	{
		$p = str_replace('#', '0', $pattern);
		$pos = strrpos($pattern, ',');

		if ($pos !== false)
		{
			$pos2 = strrpos(substr($p, 0, $pos), ',');
			$format['group_size1'] = strrpos($p, '0') - $pos;
			$format['group_size2'] = $pos2 !== false ? $pos - $pos2 - 1 : 0;
		}
	}
}
