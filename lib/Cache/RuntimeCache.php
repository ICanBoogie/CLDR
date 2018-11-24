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

final class RuntimeCache implements Cache
{
	private $cache = [];

	/**
	 * @inheritdoc
	 */
	public function get($path)
	{
		return isset($this->cache[$path]) ? $this->cache[$path] : null;
	}

	/**
	 * @inheritdoc
	 */
	public function set($path, array $data)
	{
		$this->cache[$path] = $data;
	}
}
