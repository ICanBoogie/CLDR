<?php

namespace ICanBoogie\CLDR\Territory;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

/**
 * @implements IteratorAggregate<RegionCurrency>
 */
final class RegionCurrencies implements IteratorAggregate
{
	/**
	 * @phpstan-ignore-next-line
	 */
	public static function from(array $data): self
	{
		$currencies = array_values(array_map(fn($currency) => RegionCurrency::from($currency), $data));

		usort($currencies, function (RegionCurrency $a, RegionCurrency $b) {
			if ($a->from && $b->from) {
				return $a->from <=> $b->from;
			} elseif ($a->to && $b->to) {
				return $b->to <=> $a->to;
			}

			return -1;
		});

		return new self($currencies);
	}

	/**
	 * @param RegionCurrency[] $currencies
	 */
	private function __construct(
		private readonly array $currencies
	) {
	}

	public function getIterator(): Traversable
	{
		return new ArrayIterator($this->currencies);
	}

	public function at(string $date): ?RegionCurrency
	{
		$currencies = array_filter(
			$this->currencies,
			function (RegionCurrency $currency) use ($date) {
				return $currency->tender
					&& ($currency->to === null || $currency->to >= $date)
					&& ($currency->from === null || $currency->from <= $date);
			}
		);

		$currencies = array_values($currencies);

		return current($currencies) ?: null;
	}
}
