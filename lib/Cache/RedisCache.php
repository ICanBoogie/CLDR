<?php

namespace ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use Redis;
use RedisCluster;

use function serialize;
use function unserialize;

/**
 * Provides CLDR data from a Redis client.
 */
final class RedisCache implements Cache
{
	public const DEFAULT_PREFIX = 'icanboogie-cldr-';

	public function __construct(
		private readonly RedisCluster|Redis $redis,
		private readonly string $prefix = self::DEFAULT_PREFIX
	) {
	}

	public function get(string $path): ?array
	{
		$data = $this->redis->get($this->prefix . $path);

		if (!$data) {
			return null;
		}

		return unserialize($data);
	}

	public function set(string $path, array $data): void
	{
		$this->redis->set($this->prefix . $path, serialize($data));
	}
}
