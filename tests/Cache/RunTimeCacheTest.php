<?php

namespace Test\ICanBoogie\CLDR\Cache;

use ICanBoogie\CLDR\Cache;
use ICanBoogie\CLDR\Cache\RuntimeCache;

class RunTimeCacheTest extends TestCase
{
	protected function makeCache(): Cache
	{
		return new RuntimeCache();
	}
}
