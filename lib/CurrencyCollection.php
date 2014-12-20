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

use ICanBoogie\OffsetNotDefined;

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
 * @package ICanBoogie\CLDR
 */
class CurrencyCollection implements \ArrayAccess
{
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CollectionTrait;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Check if a currency is defined.
	 *
	 * @param string $currency_code
	 *
	 * @return bool `true` if the currency is defined, `false' otherwise.
	 */
	public function offsetExists($currency_code)
	{
		$data = $this->repository->supplemental['currencyData']['region'];

		foreach ($data as $territory_code => $currencies)
		{
			foreach ($currencies as $currency_info)
			{
				$code = key($currency_info);

				if ($code == $currency_code)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Return a currency.
	 *
	 * @param string $currency_code
	 *
	 * @throw OffsetNotDefined in attempt to obtain a currency that is not defined.
	 *
	 * @return Currency
	 */
	public function offsetGet($currency_code)
	{
		if (isset($this->collection[$currency_code]))
		{
			return $this->collection[$currency_code];
		}

		if (!$this->offsetExists($currency_code))
		{
			throw new OffsetNotDefined([ $currency_code, $this ]);
		}

		return $this->collection[$currency_code] = new Currency($this->repository, $currency_code);
	}
}
