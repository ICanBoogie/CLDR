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
 * @extends AbstractCollection<Territory>
 */
final class TerritoryCollection extends AbstractCollection
{
	public function __construct(
		public readonly Repository $repository
	) {
		parent::__construct(function (string $territory_code): Territory {

			$this->assert_defined($territory_code);

			return new Territory($this->repository, $territory_code);

		});
	}

	/**
	 * Checks if a territory exists.
	 *
	 * @param string $offset Territory ISO code.
	 */
	public function offsetExists($offset): bool
	{
		$supplemental = $this->repository->supplemental;

		return isset($supplemental['territoryInfo'][ $offset ])
			|| isset($supplemental['territoryContainment'][ $offset ]);
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
