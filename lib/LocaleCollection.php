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

use ICanBoogie\OffsetNotWritable;

/**
 * Representation of a locale collection.
 */
class LocaleCollection implements \ArrayAccess
{
	/**
	 * Representation of a CLDR.
	 *
	 * @var Repository
	 */
	protected $repository;

	/**
	 * Locale instances.
	 *
	 * @var Locale[]
	 */
	protected $collection = array();

	/**
	 * Initializes the {@link $repository} property.
	 *
	 * @param Repository $repository Representation of a CLDR.
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
			$this->collection[$offset] = new Locale($this->repository, $offset);
		}

		return $this->collection[$offset];
	}

	public function offsetSet($offset, $value)
	{
		throw new OffsetNotWritable(array($offset, $this));
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable(array($offset, $this));
	}
}
