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
 * @method Currency offsetGet($id)
 */
class CurrencyCollection extends AbstractCollection
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function ($currency_code) {

            $this->assert_defined($currency_code);

            return new Currency($this->repository, $currency_code);

        });
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

				if ($code === $currency_code)
				{
					return true;
				}
			}
		}

		return false;
	}

    /**
     * Asserts that a currency is defined.
     *
     * @param string $currency_code
     *
     * @throws CurrencyNotDefined if the specified currency is not defined.
     */
    public function assert_defined($currency_code)
    {
        if ($this->offsetExists($currency_code))
        {
            return;
        }

        throw new CurrencyNotDefined($currency_code);
    }
}
