<?php

namespace ICanBoogie\CLDR;

use ArrayAccess;
use BadMethodCallException;
use Closure;

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
	private array $collection = [];

	/**
	 * @param Closure(string):T $create_instance
	 */
	public function __construct(
		private readonly Closure $create_instance
	) {
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
	 *
	 * @return T
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		return $this->collection[$offset] ??= ($this->create_instance)($offset);
	}
}
