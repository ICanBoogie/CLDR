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

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\OffsetNotDefined;

abstract class AbstractSectionCollection implements \ArrayAccess
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

	/**
	 * Initializes the {@link $repository} property.
	 *
	 * @param Repository $repository
	 * @param string $name
	 * @param array $available_sections
	 */
	public function __construct(Repository $repository, $name, array $available_sections)
	{
		$this->repository = $repository;
		$this->name = $name;
		$this->available_sections = $available_sections;
	}

	/**
	 * @inheritdoc
	 */
	public function offsetExists($offset)
	{
		return isset($this->available_sections[$offset]);
	}

	/**
	 * @inheritdoc
	 */
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
