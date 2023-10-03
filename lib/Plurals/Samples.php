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
use IteratorAggregate;
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
 * @internal
 *
 * @implements IteratorAggregate<string>
 *
 * @see http://unicode.org/reports/tr35/tr35-numbers.html#Samples
 */
final class Samples implements IteratorAggregate
{
	/**
	 * @private
	 */
	public const INFINITY = '…';

	/**
	 * @private
	 */
	public const SAMPLE_RANGE_SEPARATOR = '~';

	static public function from(string $samples): Samples
	{
		return SamplesCache::get(
			$samples,
			static fn(): Samples => new self(self::parse_rules($samples))
		);
	}

	/**
	 * @return array<string|string[]>
	 */
	static private function parse_rules(string $sample_string): array
	{
		$samples = [];
		$type_and_samples_string_list = array_slice(explode('@', $sample_string), 1);

		foreach ($type_and_samples_string_list as $type_and_samples_string)
		{
			[ $type, $samples_string ] = explode(' ', trim($type_and_samples_string), 2);

			$samples[$type] = self::parse_samples($samples_string);
		}

		return array_merge(...array_values($samples));
	}

	/**
	 * Parse a samples string.
	 *
	 *
	 * @return array<string|string[]>
	 */
	static private function parse_samples(string $samples_string): array
	{
		$samples = [];

		foreach (explode(', ', $samples_string) as $sample)
		{
			if ($sample === self::INFINITY)
			{
				continue;
			}

			if (!str_contains($sample, self::SAMPLE_RANGE_SEPARATOR))
			{
				$samples[] = $sample;

				continue;
			}

			[ $start, $end ] = explode(self::SAMPLE_RANGE_SEPARATOR, $sample);

			$samples[] = [ $start, $end ];
		}

		return $samples;
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	static private function precision_from(float|int|string $number): int
	{
		return Number::precision_from($number);
	}

	/**
	 * @param array<string|string[]> $samples
	 */
	private function __construct(
		private readonly array $samples
	) {
	}

	/**
	 * Note: The iterator yields numeric strings to avoid '0.30000000000000004' when '0.3' is correct,
	 * and to avoid removing trailing zeros e.g. '1.0' or '1.00'.
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

			/**
			 * @var numeric-string $start
			 * @var numeric-string $end
			 */

			[ $start, $end ] = $sample;

			$precision = self::precision_from($start) ?: self::precision_from($end);
			$step = 1 / (int) ('1' . str_repeat('0', $precision));
			$start += 0;
			$end += 0;

			// we use a for/times, so we don't lose quantities, compared to a $start += $step
			$times = ($end - $start) / $step;

			for ($i = 0 ; $i < $times + 1 ; $i++)
			{
				yield (string) ($start + $step * $i);
			}
		}
	}
}
