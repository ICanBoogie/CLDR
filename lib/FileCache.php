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