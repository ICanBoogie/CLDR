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
use function serialize;
use function unserialize;

/**
 * Provides CLDR data from a Redis client.
 */
class RedisCache implements Cache
{
	const DEFAULT_PREFIX = 'icanboogie-cldr-';

	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var string
	 */
	private $prefix;

	public function __construct($redis, $prefix = self::DEFAULT_PREFIX)
	{
		$this->redis = $redis;
		$this->prefix = $prefix;
	}

	/**
	 * @inheritdoc
	 */
	public function get($path)
	{
		$data = $this->redis->get($this->prefix . $path);

		if (!$data) {
			return null;
		}

		return unserialize($data);
	}

	/**
	 * @inheritdoc
	 */
	public function set($path, array $data)
	{
		$this->redis->set($this->prefix . $path, serialize($data));
	}
}
