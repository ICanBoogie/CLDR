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

class NumberFormatter
{
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

	static public function round_to($number, $precision)
	{
		return round($number, $precision);
	}

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

	static private $tokenize_cache = array();

	/**
	 * Parses a given string pattern.
	 *
	 * @param string $pattern The pattern to be parsed.
	 * @param string $minus_sign The minus sign.
	 *
	 * @return array The parsed pattern.
	 */
	static protected function tokenize($pattern, $minus_sign)
	{
		$cache_key = $pattern . ';' . $minus_sign;

		if (isset(self::$tokenize_cache[$cache_key]))
		{
			return self::$tokenize_cache[$cache_key];
		}

		$format = array
		(
			'positivePrefix' => '',
			'positiveSuffix' => '',
			'negativePrefix' => '',
			'negativeSuffix' => ''
		);

		#
		# Positive and negative patterns
		#

		$patterns = explode(';', $pattern);

		if (preg_match('/^(.*?)[#,\.0]+(.*?)$/', $patterns[0], $matches))
		{
			$format['positivePrefix'] = $matches[1];
			$format['positiveSuffix'] = $matches[2];
		}

		if (isset($patterns[1]) && preg_match('/^(.*?)[#,\.0]+(.*?)$/', $patterns[1], $matches))
		{
			$format['negativePrefix'] = $matches[1];
			$format['negativeSuffix'] = $matches[2];
		}
		else
		{
			$format['negativePrefix'] = $minus_sign . $format['positivePrefix'];
			$format['negativeSuffix'] = $format['positiveSuffix'];
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
		else
		{
			$format['multiplier'] = 1;
		}

		#
		# Decimal part
		#

		$pos = strpos($pat,'.');

		if ($pos !== false)
		{
			$pos2 = strrpos($pat, '0');

			if ($pos2 > $pos)
			{
				$format['decimalDigits'] = $pos2 - $pos;
			}
			else
			{
				$format['decimalDigits'] = 0;
			}

			$pos3 = strrpos($pat, '#');

			if ($pos3 >= $pos2)
			{
				$format['maxDecimalDigits'] = $pos3 - $pos;
			}
			else
			{
				$format['maxDecimalDigits'] = $format['decimalDigits'];
			}

			$pat = substr($pat, 0, $pos);
		}
		else
		{
			$format['decimalDigits'] = 0;
			$format['maxDecimalDigits'] = 0;
		}

		#
		# Interger part
		#

		$p = str_replace(',', '', $pat);
		$pos = strpos($p, '0');

		if ($pos !== false)
		{
			$format['integerDigits'] = strrpos($p, '0') - $pos + 1;
		}
		else
		{
			$format['integerDigits'] = 0;
		}

		#
		# Group sizes
		#

		$p = str_replace('#', '0', $pat);
		$pos = strrpos($pat, ',');

		if ($pos !== false)
		{
			$format['groupSize1'] = strrpos($p, '0') - $pos;

			if (($pos2 = strrpos(substr($p, 0, $pos), ',')) !== false)
			{
				$format['groupSize2'] = $pos - $pos2 - 1;
			}
			else
			{
				$format['groupSize2'] = 0;
			}
		}
		else
		{
			$format['groupSize1'] = $format['groupSize2'] = 0;
		}

		return self::$tokenize_cache[$cache_key] = $format;
	}

	protected $default_symbols = array
	(
		'group' => ',',
		'decimal' => '.',
		'plus_sign' => '+',
		'minus_sign' => '-'
	);

	/**
	 * @var Numbers
	 */
	protected $numbers;

	public function __construct(Numbers $numbers)
	{
		$this->numbers = $numbers;
	}

	protected function default_format_options_for($number)
	{
		return array(

			'decimalDigits' => 0,
	        'maxDecimalDigits' => 0,
	        'integerDigits' => 0,
	        'groupSize1' => 0,
			'groupSize2'=> 0,
			'positivePrefix' => '+',
			'positiveSuffix' => '',
			'negativePrefix' => '-',
			'negativeSuffix' => '',
			'multiplier' => 1,
			'currency_symbol' => null

		);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param mixed $number The number to be formatted.
	 * @param string $pattern The pattern used to format the number.
	 * @param array $options Options.
	 *
	 * @return string The formatted number.
	 */
	public function format($number, $pattern, array $options=array())
	{
		$options += self::tokenize($pattern, "-") + $this->default_format_options_for($number);

		$formatted_number = $this->format_number($number, $options);

		if (isset($options['currency_symbol']))
		{
			$formatted_number = str_replace("¤", $options['currency_symbol'], $formatted_number);
		}

		return $formatted_number;
	}

	/**
	 * Formats a number based on a format.
	 * This is the method that does actual number formatting.
	 * @param array $format format with the following structure:
	 * <pre>
	 * array(
	 * 	'decimalDigits'=>2,     // number of required digits after decimal point; 0s will be padded if not enough digits; if -1, it means we should drop decimal point
	 *  'maxDecimalDigits'=>3,  // maximum number of digits after decimal point. Additional digits will be truncated.
	 * 	'integerDigits'=>1,     // number of required digits before decimal point; 0s will be padded if not enough digits
	 * 	'groupSize1'=>3,        // the primary grouping size; if 0, it means no grouping
	 * 	'groupSize2'=>0,        // the secondary grouping size; if 0, it means no secondary grouping
	 * 	'positivePrefix'=>'+',  // prefix to positive number
	 * 	'positiveSuffix'=>'',   // suffix to positive number
	 * 	'negativePrefix'=>'(',  // prefix to negative number
	 * 	'negativeSuffix'=>')',  // suffix to negative number
	 * 	'multiplier'=>1,        // 100 for percent, 1000 for per mille
	 * );
	 * </pre>
	 * @param mixed $value the number to be formatted
	 * @return string the formatted result
	 */
	private function format_number($value, array $format)
	{
		$negative = $value < 0;
		$value = abs($value * $format['multiplier']);

		if ($format['maxDecimalDigits'] >= 0)
		{
			$value = round($value, $format['maxDecimalDigits']);
		}

		$value = "$value";

		if (($pos = strpos($value, '.')) !== false)
		{
			$integer = substr($value, 0, $pos);
			$decimal = substr($value, $pos + 1);
		}
		else
		{
			$integer = $value;
			$decimal = '';
		}

		if ($format['decimalDigits'] > strlen($decimal))
		{
			$decimal = str_pad($decimal, $format['decimalDigits'], '0');
		}

		if (strlen($decimal))
		{
			$decimal = $this->numbers->symbols['decimal'] . $decimal;
		}

		$integer = str_pad($integer, $format['integerDigits'], '0', STR_PAD_LEFT);

		if ($format['groupSize1'] > 0 && strlen($integer) > $format['groupSize1'])
		{
			$str1 = substr($integer, 0, -$format['groupSize1']);
			$str2 = substr($integer, -$format['groupSize1']);
			$size = $format['groupSize2'] > 0 ? $format['groupSize2'] : $format['groupSize1'];
			$str1 = str_pad($str1, (int) ((strlen($str1) + $size - 1) / $size) * $size, ' ', STR_PAD_LEFT);
			$integer = ltrim(implode($this->numbers->symbols['group'], str_split($str1, $size))) . $this->numbers->symbols['group'] . $str2;
		}

		if ($negative)
		{
			$number = $format['negativePrefix'] . $integer . $decimal . $format['negativeSuffix'];
		}
		else
		{
			$number = $format['positivePrefix'] . $integer . $decimal . $format['positiveSuffix'];
		}

		return strtr($number, array(
			'%' => $this->numbers->symbols['percentSign'],
			'‰' => $this->numbers->symbols['perMille'] )
		);
	}
}
