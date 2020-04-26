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

use ArrayAccess;
use BadMethodCallException;

/**
 * An abstract collection.
 */
abstract class AbstractCollection implements ArrayAccess
{
	use CollectionTrait;

	/**
	 * @var object[]
	 */
	private $collection = [];

	/**
	 * @var callable
	 */
	private $create_instance;

	public function __construct(callable $create_instance)
	{
		$this->create_instance = $create_instance;
	}

	/**
	 * @inheritDoc
	 *
	 * @throws BadMethodCallException
	 */
	public function offsetExists($id): bool
	{
		throw new BadMethodCallException("The method is not implemented");
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($id)
	{
		if (empty($this->collection[$id]))
		{
			$create_instance = $this->create_instance;
			$this->collection[$id] = $create_instance($id);
		}

		return $this->collection[$id];
	}
}
