<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Provider;

use ICanBoogie\CLDR\Cache;
use ICanBoogie\CLDR\Provider;

/**
 * Decorate a provider with caching features.
 */
final class CachedProvider implements Provider
{
	public function __construct(
		private readonly Provider $provider,
		private readonly Cache $cache
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function provide(string $path): array
	{
		$data = $this->cache->get($path);

		if ($data !== null)
		{
			return $data;
		}

		$data = $this->provider->provide($path);

		$this->cache->set($path, $data);

		return $data;
	}
}
