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

class NumberPatternParser
{
	const PATTERN_REGEX = '/^(.*?)[#,\.0]+(.*?)$/';

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
	 *
	 * @param string $pattern The pattern to be parsed.
	 *
	 * @return array The parsed pattern.
	 */
	static public function parse($pattern)
	{
		$format = self::$initial_format;

		self::parse_multiple_patterns($pattern, $format);
		self::parse_multiplier($pattern, $format);
		self::parse_decimal_part($pattern, $format);
		self::parse_integer_part($pattern, $format);
		self::parse_group_sizes($pattern, $format);

		return $format;
	}

	/**
	 * @param string $pattern
	 * @param array $format
	 */
	static private function parse_multiple_patterns(&$pattern, array &$format)
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

	/**
	 * @param string $pattern
	 * @param array $format
	 */
	static private function parse_multiplier(&$pattern, array &$format)
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

	/**
	 * @param string $pattern
	 * @param array $format
	 */
	static private function parse_decimal_part(&$pattern, array &$format)
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

	/**
	 * @param string $pattern
	 * @param array $format
	 */
	static private function parse_integer_part(&$pattern, array &$format)
	{
		$p = str_replace(',', '', $pattern);
		$pos = strpos($p, '0');

		$format['integer_digits'] = $pos !== false
			? strrpos($p, '0') - $pos + 1
			: 0;
	}

	/**
	 * @param string $pattern
	 * @param array $format
	 */
	static private function parse_group_sizes(&$pattern, array &$format)
	{
		$p = str_replace('#', '0', $pattern);
		$pos = strrpos($pattern, ',');

		if ($pos !== false)
		{
			$format['group_size1'] = strrpos($p, '0') - $pos;

			$pos2 = strrpos(substr($p, 0, $pos), ',');
			$format['group_size2'] = $pos2 !== false
				? $pos - $pos2 - 1
				: 0;
		}
	}
}
