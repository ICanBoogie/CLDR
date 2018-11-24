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
use function uniqid;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
	public function testCache()
	{
		$cache = $this->makeCache();
		$this->assertNull($cache->get($path = $this->generatePath()));
		$cache->set($path, $data = [ uniqid() => uniqid() ]);
		$this->assertSame($data, $cache->get($path));
		$cache->set($path, $data = [ uniqid() => uniqid() ]);
		$this->assertSame($data, $cache->get($path));
	}

	/**
	 * @return Cache
	 */
	abstract protected function makeCache();

	/**
	 * @return string
	 */
	private function generatePath()
	{
		return uniqid() . '/' . uniqid();
	}
}
