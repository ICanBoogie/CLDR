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
use function key;

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
 */
final class CurrencyCollection extends AbstractCollection
{
	/**
	 * @uses get_repository
	 */
	use AccessorTrait;
	use RepositoryPropertyTrait;

	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function (string $currency_code): Currency {

			$this->assert_defined($currency_code);

			return new Currency($this->repository, $currency_code);

		});
	}

	/**
	 * Checks if a currency exists.
	 *
	 * @param string $offset Currency code.
	 */
	public function offsetExists($offset): bool
	{
		$data = $this->repository->supplemental['currencyData']['region'];

		foreach ($data as $currencies)
		{
			foreach ($currencies as $currency_info)
			{
				$code = key($currency_info);

				if ($code === $offset)
				{
					return true;
				}
			}
		}

		return false;
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
