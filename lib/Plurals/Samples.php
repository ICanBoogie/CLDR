<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Plurals;

use ICanBoogie\CLDR\Number;

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Samples
 */
final class Samples implements \IteratorAggregate
{
	const INFINITY = '…';
	const SAMPLE_RANGE_SEPARATOR = '~';
	const TYPE_INTEGER = 'integer';
	const TYPE_DECIMAL = 'decimal';

	/**
	 * @var array
	 */
	static private $instances = [];

	/**
	 * @param string $samples_string
	 *
	 * @return Samples
	 */
	static public function from($samples_string)
	{
		$instance = &self::$instances[$samples_string];

		return $instance ?: $instance = new static(self::parse_rules($samples_string));
	}

	/**
	 * @param string $sample_string Plural rules.
	 *
	 * @return array An array of samples.
	 */
	static private function parse_rules($sample_string)
	{
		$samples = [];
		$type_and_samples_string_list = array_slice(explode('@', $sample_string), 1);

		foreach ($type_and_samples_string_list as $type_and_samples_string)
		{
			list($type, $samples_string) = explode(' ', trim($type_and_samples_string), 2);

			$samples[$type] = self::parse_samples($type, $samples_string);
		}

		return call_user_func_array('array_merge', $samples);
	}

	/**
	 * Parse a samples string.
	 *
	 * @param string $type One of `TYPE_*`
	 * @param string $samples_string
	 *
	 * @return array
	 */
	static private function parse_samples($type, $samples_string)
	{
		$samples = [];

		foreach (explode(', ', $samples_string) as $sample)
		{
			if ($sample === self::INFINITY)
			{
				continue;
			}

			if (strpos($sample, self::SAMPLE_RANGE_SEPARATOR) === false)
			{
				$samples[] = self::cast($type, $sample);

				continue;
			}

			list($start, $end) = explode(self::SAMPLE_RANGE_SEPARATOR, $sample);

			$samples[] = [ self::cast($type, $start), self::cast($type, $end) ];
		}

		return $samples;
	}

	/**
	 * @param string $type One of `TYPE_*`.
	 * @param number $number
	 *
	 * @return float|int
	 */
	static private function cast($type, $number)
	{
		return $type === self::TYPE_DECIMAL ? (float) $number : (int) $number;
	}

	/**
	 * @param number $number
	 *
	 * @return int
	 */
	static private function precision_from($number)
	{
		return Number::precision_from($number);
	}

	/**
	 * @var array
	 */
	private $samples;

	/**
	 * @param array $samples
	 */
	private function __construct(array $samples)
	{
		$this->samples = $samples;
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator()
	{
		foreach ($this->samples as $sample)
		{
			if (!is_array($sample))
			{
				yield $sample;

				continue;
			}

			list($start, $end) = $sample;

			$precision = self::precision_from($start) ?: self::precision_from($end);
			$step = 1 / (int) ('1' . str_repeat('0', $precision));

			// we use a for/times so we don't loose quantities, compared to a $start += $step
			$times = ($end - $start) / $step;

			for ($i = 0 ; $i < $times + 1 ; $i++)
			{
				yield (string) ($start + $step * $i);
			}
		}
	}
}
