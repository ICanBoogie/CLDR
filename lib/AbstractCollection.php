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
use ReturnTypeWillChange;

/**
 * An abstract collection.
 *
 * @template T
 * @implements ArrayAccess<string, T>
 */
abstract class AbstractCollection implements ArrayAccess
{
	use CollectionTrait;

	/**
	 * @var array<string, T>
	 */
	private $collection = [];

	/**
	 * @var callable(string): T
	 */
	private $create_instance;

	public function __construct(callable $create_instance)
	{
		$this->create_instance = $create_instance;
	}

	/**
	 * @param string $offset
	 *
	 * @throws BadMethodCallException
	 */
	public function offsetExists($offset): bool
	{
		throw new BadMethodCallException("The method is not implemented");
	}

	/**
	 * @param string $offset
	 */
	#[ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		if (empty($this->collection[$offset]))
		{
			$create_instance = $this->create_instance;
			$this->collection[$offset] = $create_instance($offset);
		}

		return $this->collection[$offset];
	}
}
