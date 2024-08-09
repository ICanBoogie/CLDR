<?php

namespace Test\ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use ICanBoogie\CLDR\Cache\FileCache;

use const Test\ICanBoogie\CLDR\CACHE_DIR;

class FileCacheTest extends TestCase
{
    protected function makeCache(): Cache
    {
        return new FileCache(CACHE_DIR);
    }
}
