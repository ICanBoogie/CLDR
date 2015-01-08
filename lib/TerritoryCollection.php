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
 * @package ICanBoogie\CLDR
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

	public function offsetExists($offset)
	{
		throw new \BadMethodCallException("The method is not implemented");
	}

	public function offsetGet($offset)
	{
		if (empty($this->collection[$offset]))
		{
			$this->collection[$offset] = new Territory($this->repository, $offset);
		}

		return $this->collection[$offset];
	}

}
