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
 * Representation of a territory collection.
 *
 * @package ICanBoogie\CLDR
 *
 * ```php
 * <?php
 *
 * $territories = new TerritoryCollection($cldr);
 * # or
 * $territories = $cldr->territories;
 *
 * // check if a territory is defined
 * isset($territories['FR']);          // true
 * isset($territories['UnDiFiNeD']);   // false
 */
class TerritoryCollection implements \ArrayAccess
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
     * Checks if a territory is defined.
     *
     * @param string $code Territory ISO code.
     *
     * @return bool `true` if the territory is defined, `false` otherwise.
     */
	public function offsetExists($code)
	{
		$supplemental = $this->repository->supplemental;

        return isset($supplemental['territoryInfo'][$code])
        || isset($supplemental['territoryContainment'][$code]);
	}

    /**
     * Returns a territory.
     *
     * @param string $code Territory ISO code.
     *
     * @return Territory
     */
	public function offsetGet($code)
	{
		if (empty($this->collection[$code]))
		{
			$this->collection[$code] = new Territory($this->repository, $code);
		}

		return $this->collection[$code];
	}

    /**
     * Asserts that a territory is defined.
     *
     * @param string $code
     *
     * @throws TerritoryNotDefined if the specified territory is not defined.
     */
    public function assert_defined($code)
    {
        if (isset($this[$code]))
        {
            return;
        }

        throw new TerritoryNotDefined($code);
    }
}
