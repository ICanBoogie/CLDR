<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;

/**
 * A collection of {@link Cache} instances.
 */
final class CacheCollection implements Cache
{
	/**
	 * @var Cache[]
	 */
	private $collection;

	/**
	 * @param Cache[] $collection
	 */
	public function __construct(array $collection)
	{
		$this->collection = $collection;
	}

	public function get(string $path): ?array
	{
		foreach ($this->collection as $cache)
		{
			$data = $cache->get($path);

			if ($data !== null) {
				return $data;
			}
		}

		return null;
	}

	public function set(string $path, array $data): void
	{
		foreach ($this->collection as $cache)
		{
			$cache->set($path, $data);
		}
	}
}
