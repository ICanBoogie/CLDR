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

	public function get(string $path): ?array
	{
		return $this->cache[$path] ?? null;
	}

	public function set(string $path, array $data): void
	{
		$this->cache[$path] = $data;
	}
}
