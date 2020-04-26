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
final class TerritoryCollection extends AbstractCollection
{
	/**
	 * @uses get_repository
	 */
	use AccessorTrait;
	use RepositoryPropertyTrait;

	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function (string $territory_code): Territory {

			$this->assert_defined($territory_code);

			return new Territory($this->repository, $territory_code);

		});
	}

	/**
	 * Whether a territory is defined.
	 *
	 * @inheritDoc
	 *
	 * @param string $territory_code Territory ISO code.
	 */
	public function offsetExists($territory_code): bool
	{
		$supplemental = $this->repository->supplemental;

		return isset($supplemental['territoryInfo'][ $territory_code ])
			|| isset($supplemental['territoryContainment'][ $territory_code ]);
	}

	/**
	 * Asserts a territory is defined.
	 *
	 * @throws TerritoryNotDefined
	 */
	public function assert_defined(string $territory_code): void
	{
		if ($this->offsetExists($territory_code))
		{
			return;
		}

		throw new TerritoryNotDefined($territory_code);
	}
}
