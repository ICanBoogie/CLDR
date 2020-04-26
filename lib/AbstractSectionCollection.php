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
use function explode;

abstract class AbstractSectionCollection implements ArrayAccess
{
	use AccessorTrait;
	use CollectionTrait;
	use RepositoryPropertyTrait;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var array
	 */
	private $available_sections;

	/**
	 * Loaded sections.
	 *
	 * @var array
	 */
	private $sections = [];

	public function __construct(Repository $repository, string $name, array $available_sections)
	{
		$this->repository = $repository;
		$this->name = $name;
		$this->available_sections = $available_sections;
	}

	/**
	 * @inheritDoc
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->available_sections[$offset]);
	}

	/**
	 * @inheritDoc
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet($offset)
	{
		$sections = &$this->sections;

		if (isset($sections[$offset]))
		{
			return $sections[$offset];
		}

		$available_sections = $this->available_sections;

		if (empty($available_sections[$offset]))
		{
			throw new OffsetNotDefined([ $offset, $this ]);
		}

		$name = $this->name;
		$data = $this->repository->fetch("$name/$offset");
		$path = "$name/{$available_sections[$offset]}";
		$path_parts = explode('/', $path);

		foreach ($path_parts as $part)
		{
			$data = $data[$part];
		}

		return $sections[$offset] = $data;
	}
}
