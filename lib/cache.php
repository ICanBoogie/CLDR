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
interface Cache
{
	/**
	 * Checks if a key exists in the cache.
	 *
	 * @param string $key
	 */
	public function exists($key);

	/**
	 * Retrieves the data specified by the key from the cache.
	 *
	 * @param string $key
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

/**
 * A cache that persists using files.
 *
 * <pre>
 * <?php
 *
 * use ICanBoogie\CLDR\FileCache;
 *
 * $cache = new FileCache('/path/to/cached_repository');
 * </pre>
 */
class FileCache implements Cache
{
	protected $root;

	static private function path_to_key($path)
	{
		return str_replace('/', '--', $path);
	}

	public function __construct($root)
	{
		$this->root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

	public function exists($path)
	{
		$key = self::path_to_key($path);
		$filename = $this->root . $key;

		return file_exists($filename);
	}

	public function retrieve($path)
	{
		$key = self::path_to_key($path);
		$filename = $this->root . $key;

		if (!file_exists($filename))
		{
			return;
		}

		return file_get_contents($filename);
	}

	public function store($path, $data)
	{
		$key = self::path_to_key($path);
		$filename = $this->root . $key;

		file_put_contents($filename, $data);
	}
}

/**
 * A cache that only persists during the run time.
 *
 * This cache is meant to be used as a wrapper to more persistant cache:
 *
 * <pre>
 * <?php
 *
 * use ICanBoogie\CLDR\FileCache;
 * use ICanBoogie\CLDR\RunTimeCache;
 *
 * $cache = new RunTimeCache(new FileCache('/path/to/cached_repository'));
 * </pre>
 */
class RunTimeCache implements Cache
{
	private $cache = array();
	private $static_cache;

	public function __construct(Cache $static_cache)
	{
		$this->static_cache = $static_cache;
	}

	public function exists($path)
	{
		if (array_key_exists($path, $this->cache))
		{
			return true;
		}

		return parent::exists($path);
	}

	public function retrieve($path)
	{
		if (array_key_exists($path, $this->cache))
		{
			return $this->cache[$path];
		}

		return $this->cache[$path] = $this->static_cache->retrieve($path);
	}

	public function store($path, $data)
	{
		$this->cache[$path] = $data;

		$this->static_cache->store($path, $data);
	}
}