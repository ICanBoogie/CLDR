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
 * An abstract collection.
 */
abstract class AbstractCollection implements \ArrayAccess
{
	use CollectionTrait;

	/**
	 * Instances.
	 *
	 * @var array
	 */
	private $collection = [];

	/**
	 * @var callable
	 */
	private $create_instance;

	/**
	 * Initializes the {@link $locale} property.
	 *
	 * @param callable $create_instance
	 */
	public function __construct(callable $create_instance)
	{
		$this->create_instance = $create_instance;
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \BadMethodCallException
	 */
	public function offsetExists($id)
	{
		throw new \BadMethodCallException("The method is not implemented");
	}

	/**
	 * @inheritdoc
	 */
	public function offsetGet($id)
	{
		if (empty($this->collection[$id]))
		{
			$this->collection[$id] = call_user_func($this->create_instance, $id);
		}

		return $this->collection[$id];
	}
}
