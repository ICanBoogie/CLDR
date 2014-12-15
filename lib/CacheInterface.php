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
 * Cache interface
 */
interface CacheInterface
{
	/**
	 * Checks if a key exists in the cache.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists($key);

	/**
	 * Retrieves the data specified by the key from the cache.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function retrieve($key);

	/**
	 * Stores the specified data in the cache, under the specified key.
	 *
	 * @param string $key
	 * @param mixed $data
	 */
	public function store($key, $data);
}
