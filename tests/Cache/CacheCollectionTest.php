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
use const ICanBoogie\CLDR\CACHE_DIR;

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
