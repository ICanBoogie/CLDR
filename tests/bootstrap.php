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

use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RedisCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\WebProvider;
use Redis;

use function getenv;

require __DIR__ . '/../vendor/autoload.php';

define('ICanBoogie\CLDR\CACHE_DIR', __DIR__ . '/../cache');

if (!file_exists(CACHE_DIR))
{
	mkdir(CACHE_DIR);
}

/**
 * @return Provider
 */
function create_provider()
{
	static $provider;

	if ($provider)
	{
		return $provider;
	}

	$redis = new Redis();

	if (!$redis->connect(getenv('ICANBOOGIE_CLDR_REDIS_HOST'), getenv('ICANBOOGIE_CLDR_REDIS_PORT'))) {
		echo "Unable to connect to Redis";

		exit(1);
	}

	return $provider = new CachedProvider(
		new WebProvider,
		new CacheCollection([
			new RuntimeCache(),
			new RedisCache($redis),
			new FileCache(CACHE_DIR)
		])
	);
}

/**
 * @return Repository
 */
function get_repository()
{
	static $repository;

	return $repository ?: $repository = new Repository(create_provider());
}

date_default_timezone_set('Europe/Madrid');
