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

use Predis\Client as Client;

/**
 * Provides CLDR data from a Redis client, and falls back to a specified provider when the data
 * is not available.
 *
 * @package ICanBoogie\CLDR
 */
class PredisProvider implements ProviderInterface, CacheInterface
{
	use ProviderChainTrait;

	/**
	 * @var Client
	 */
	private $client;

	/**
	 * @param ProviderInterface $provider Fallback provider.
	 * @param Client $client
	 */
	public function __construct(ProviderInterface $provider, Client $client)
	{
		$this->provider = $provider;
		$this->client = $client;
	}

	/**
	 * Create a store key from a CLDR path.
	 *
	 * @param string $path A CLDR path.
	 *
	 * @return string A store key.
	 */
	private function path_to_key($path)
	{
		return "icanboogie.cldr." . str_replace('/', '--', $path);
	}

	/**
	 * Checks if a key exists in the cache.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function exists($key)
	{
		return $this->client->exists($this->path_to_key($key));
	}

	/**
	 * Retrieves the data specified by the key from the cache.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function retrieve($key)
	{
		if (!$this->exists($key))
		{
			return null;
		}

		return json_decode($this->client->get($this->path_to_key($key)), true);
	}

	/**
	 * Stores the specified data in the cache, under the specified key.
	 *
	 * @param string $key
	 * @param mixed $data
	 */
	public function store($key, $data)
	{
		$this->client->set($this->path_to_key($key), json_encode($data));
	}
}
