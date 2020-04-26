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
use Traversable;

use function array_merge;
use function array_slice;
use function array_values;
use function explode;
use function is_array;
use function str_repeat;
use function strpos;
use function trim;

/**
 * Representation of plural samples.
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Samples
 */
final class Samples implements \IteratorAggregate
{
	public const INFINITY = '…';
	public const SAMPLE_RANGE_SEPARATOR = '~';
	public const TYPE_INTEGER = 'integer';
	public const TYPE_DECIMAL = 'decimal';

	/**
	 * @var Samples[]
	 */
	static private $instances = [];

	static public function from(string $samples_string): Samples
	{
		$instance = &self::$instances[$samples_string];

		return $instance ?? $instance = new self(self::parse_rules($samples_string));
	}

	static private function parse_rules(string $sample_string): array
	{
		$samples = [];
		$type_and_samples_string_list = array_slice(explode('@', $sample_string), 1);

		foreach ($type_and_samples_string_list as $type_and_samples_string)
		{
			[ $type, $samples_string ] = explode(' ', trim($type_and_samples_string), 2);

			$samples[$type] = self::parse_samples($type, $samples_string);
		}

		return array_merge(...array_values($samples));
	}

	/**
	 * Parse a samples string.
	 *
	 * @param string $type One of `TYPE_*`
	 */
	static private function parse_samples(string $type, string $samples_string): array
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

			[ $start, $end ] = explode(self::SAMPLE_RANGE_SEPARATOR, $sample);

			$samples[] = [ self::cast($type, $start), self::cast($type, $end) ];
		}

		return $samples;
	}

	/**
	 * @param string $type One of `TYPE_*`.
	 * @param int|float $number
	 *
	 * @return int|float
	 */
	static private function cast(string $type, $number)
	{
		return $type === self::TYPE_DECIMAL ? (float) $number : (int) $number;
	}

	/**
	 * @param int|float $number
	 */
	static private function precision_from($number): int
	{
		return Number::precision_from($number);
	}

	/**
	 * @var array
	 */
	private $samples;

	private function __construct(array $samples)
	{
		$this->samples = $samples;
	}

	/**
	 * @inheritDoc
	 */
	public function getIterator(): Traversable
	{
		foreach ($this->samples as $sample)
		{
			if (!is_array($sample))
			{
				yield $sample;

				continue;
			}

			[ $start, $end ] = $sample;

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
