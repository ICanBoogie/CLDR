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

use function array_combine;
use function array_keys;

/**
 * A currency collection.
 *
 * ```php
 * <?php
 *
 * $collection = new CurrencyCollection($repository);
 *
 * isset($collection['EUR']);            // true
 * isset($collection['USD']);            // true
 * isset($collection['ABC']);            // false
 *
 * echo get_class($collection['EUR']);   // ICanBoogie\CLDR\Currency
 * echo $collection['EUR']->code;        // EUR
 * ```
 *
 * @extends AbstractCollection<Currency>
 *
 * @property-read string[] $codes Alphabetic list of currency codes.
 */
final class CurrencyCollection extends AbstractCollection
{
	/**
	 * @uses lazy_get_codes
	 */
	use AccessorTrait;

	public function __construct(
		public readonly Repository $repository
	) {
		parent::__construct(function (string $currency_code): Currency {

			$this->assert_defined($currency_code);

			return new Currency($this->repository, $currency_code);

		});
	}

	/**
	 * @return string[]
	 *
	 * @throws ResourceNotFound
	 *
	 * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-numbers-modern/main/en-001/currencies.json
	 */
	private function lazy_get_codes(): array
	{
		$codes = array_keys($this->repository->fetch(
			'numbers/en-001/currencies',
			'main/en-001/numbers/currencies'
		));

		return array_combine($codes, $codes);
	}

	/**
	 * Checks if a currency exists.
	 *
	 * @param string $offset Currency code.
	 */
	public function offsetExists($offset): bool
	{
		$codes = $this->codes;

		return isset($codes[$offset]);
	}

	/**
	 * Asserts a currency is defined.
	 *
	 * @throws CurrencyNotDefined
	 */
	public function assert_defined(string $currency_code): void
	{
		if (!$this->offsetExists($currency_code))
		{
			throw new CurrencyNotDefined($currency_code);
		}
	}
}
