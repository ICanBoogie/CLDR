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
 * A trait for classes implementing collection.
 *
 * @package ICanBoogie\CLDR
 */
trait CollectionTrait
{
	private $collection = [];

	/**
	 * @param string $offset
	 * @param mixed $value
	 *
	 * @throw OffsetNotWritable in attempt to set the offset.
	 */
	public function offsetSet($offset, $value)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	/**
	 * @param string $offset
	 *
	 * @throw OffsetNotWritable in attempt to unset the offset.
	 */
	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}
}
