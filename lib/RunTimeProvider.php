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
 * Provides CLDR data from an array, and falls back to a specified provider when the data
 * is not available.
 */
class RunTimeProvider implements ProviderInterface, CacheInterface
{
	private $store = array();
	private $provider;

	/**
	 * @param ProviderInterface $provider Fallback provider.
	 */
	public function __construct(ProviderInterface $provider)
	{
		$this->provider = $provider;
	}

	public function exists($path)
	{
		return array_key_exists($path, $this->store);
	}

	public function retrieve($path)
	{
		if (!$this->exists($path))
		{
			return;
		}

		return $this->store[ $path ];
	}

	public function store($path, $data)
	{
		$this->store[$path] = $data;
	}

	/**
	 * The section path, following the pattern "<identity>/<section>".
	 *
	 * @param string $path
	 *
	 * @throws ResourceNotFound when the specified path does not exists on the CLDR source.
	 *
	 * @return string
	 */
	public function provide($path)
	{
		if ($this->exists($path))
		{
			return $this->retrieve($path);
		}

		$data = $this->provider->provide($path);
		$this->store($path, $data);

		return $data;
	}
}
