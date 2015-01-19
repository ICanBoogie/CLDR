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
 * Provides CLDR data from the filesystem, and falls back to a specified provider when the data
 * is not available.
 */
class FileProvider implements ProviderInterface, CacheInterface
{
	use ProviderChainTrait;

	/**
	 * Create a store key from a CLDR path.
	 *
	 * @param string $path A CLDR path.
	 *
	 * @return string A store key.
	 */
	static private function path_to_key($path)
	{
		return str_replace('/', '--', $path);
	}

	/**
	 * The directory where files are stored.
	 *
	 * @var string
	 */
	protected $root;

	/**
	 * @param ProviderInterface $provider Fallback provider.
	 * @param string $directory Path to the directory where cached files are stored.
	 */
	public function __construct(ProviderInterface $provider, $directory)
	{
		$this->root = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->provider = $provider;
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

		return json_decode(file_get_contents($filename), true);
	}

	public function store($path, $data)
	{
		$key = self::path_to_key($path);
		$filename = $this->root . $key;

		file_put_contents($filename, json_encode($data));
	}
}
