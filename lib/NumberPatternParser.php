<?php

namespace ICanBoogie\CLDR;

class NumberPatternParser
{
	/**
	 * Parses a given string pattern.
	 *
	 * @param string $pattern The pattern to be parsed.
	 *
	 * @return array The parsed pattern.
	 */
	static public function parse($pattern)
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
}
