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
use Redis;

class RedisCacheTest extends TestCase
{
	/**
	 * @return Cache
	 */
	protected function makeCache()
	{
		$redis = new Redis();
		$redis->connect(getenv('ICANBOOGIE_CLDR_REDIS_HOST'), getenv('ICANBOOGIE_CLDR_REDIS_PORT'));

		return new RedisCache($redis);
	}
}
