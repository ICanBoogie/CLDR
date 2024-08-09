<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RedisCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;
use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocaleId;
use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\WebProvider;
use ICanBoogie\CLDR\Repository;
use Redis;

use function getenv;

require __DIR__ . '/../vendor/autoload.php';

const CACHE_DIR = __DIR__ . '/../' . FileCache::RECOMMENDED_DIR;

if (!file_exists(CACHE_DIR)) {
    mkdir(CACHE_DIR);
}

function create_provider(): Provider
{
    static $provider;

    if ($provider) {
        return $provider;
    }

    $redis = new Redis();
    $host = getenv('ICANBOOGIE_CLDR_REDIS_HOST');
    $port = getenv('ICANBOOGIE_CLDR_REDIS_PORT');

    assert($host !== false && strlen($host) > 0);
    assert($port !== false && strlen($port) > 0);

    if (!$redis->connect($host, (int)$port)) {
        echo "Unable to connect to Redis";

        exit(1);
    }

    return $provider = new CachedProvider(
        new WebProvider(),
        new CacheCollection([
            new RuntimeCache(),
            new RedisCache($redis),
            new FileCache(CACHE_DIR)
        ])
    );
}

function get_repository(): Repository
{
    static $repository;

    return $repository ??= new Repository(create_provider());
}

function locale_for(string|LocaleId $id): Locale
{
    return get_repository()->locale_for($id);
}

date_default_timezone_set('Europe/Madrid');
