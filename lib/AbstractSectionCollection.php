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
use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\OffsetNotDefined;

/**
 * @implements ArrayAccess<string, array>
 *
 * @property-read Repository $repository
 */
abstract class AbstractSectionCollection implements ArrayAccess
{
	use AccessorTrait;
	use CollectionTrait;
	use RepositoryPropertyTrait;

	/**
	 * @var Repository
	 */
	private $repository;

	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}

	abstract public function offsetExists($offset): bool;

	/**
	 * @var array<string, array>
	 *     Loaded sections, where _key_ is a section name and _value_ its data.
	 *
	 * @phpstan-ignore-next-line
	 */
	private $sections = [];

	/**
	 * @param string $offset
	 *
	 * @throws OffsetNotDefined
	 * @throws ResourceNotFound
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset) /* @phpstan-ignore-line */
	{
		if (!$this->offsetExists($offset))
		{
			throw new OffsetNotDefined([ $offset, $this ]);
		}

		return $this->sections[$offset]
			?? $this->sections[$offset] = $this->repository->fetch(
				$this->path_for($offset),
				$this->data_path_for($offset)
			);
	}

	/**
	 * Returns the CLDR path for the offset.
	 */
	abstract protected function path_for(string $offset): string;

	/**
	 * Returns the data path for the offset.
	 */
	abstract protected function data_path_for(string $offset): string;
}
