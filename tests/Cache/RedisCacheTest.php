<?php

namespace Test\ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use ICanBoogie\CLDR\Cache\RedisCache;
use Redis;

class RedisCacheTest extends TestCase
{
	protected function makeCache(): Cache
	{
		$redis = new Redis();
		// @phpstan-ignore-next-line
		$redis->connect(getenv('ICANBOOGIE_CLDR_REDIS_HOST'), getenv('ICANBOOGIE_CLDR_REDIS_PORT'));

		return new RedisCache($redis);
	}
}
