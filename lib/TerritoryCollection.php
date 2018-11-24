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
 * ```php
 * <?php
 *
 * $territories = new TerritoryCollection($cldr);
 * # or
 * $territories = $cldr->territories;
 *
 * // check if a territory is defined
 * isset($territories['FR']);          // true
 * isset($territories['MADONNA']);     // false
 *
 * @method Territory offsetGet($id)
 */
class TerritoryCollection extends AbstractCollection
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function ($territory_code) {

		    $this->assert_defined($territory_code);

            return new Territory($this->repository, $territory_code);

        });
	}

    /**
     * Checks if a territory is defined.
     *
     * @param string $territory_code Territory ISO code.
     *
     * @return bool `true` if the territory is defined, `false` otherwise.
     */
	public function offsetExists($territory_code)
	{
		$supplemental = $this->repository->supplemental;

        return isset($supplemental['territoryInfo'][$territory_code])
        || isset($supplemental['territoryContainment'][$territory_code]);
	}

    /**
     * Asserts that a territory is defined.
     *
     * @param string $territory_code
     *
     * @throws TerritoryNotDefined if the specified territory is not defined.
     */
    public function assert_defined($territory_code)
    {
        if ($this->offsetExists($territory_code))
        {
            return;
        }

        throw new TerritoryNotDefined($territory_code);
    }
}
