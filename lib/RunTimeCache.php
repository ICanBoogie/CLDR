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
 * A cache that only persists during the run time.
 *
 * This cache is meant to be used as a wrapper to more persistent cache:
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
