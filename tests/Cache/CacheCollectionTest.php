<?php

namespace Test\ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;

use const Test\ICanBoogie\CLDR\CACHE_DIR;

class CacheCollectionTest extends TestCase
{
    protected function makeCache(): Cache
    {
        return new CacheCollection([

            new RuntimeCache(),
            new FileCache(CACHE_DIR),

        ]);
    }
}
