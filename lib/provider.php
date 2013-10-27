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

class Provider
{
	protected $cache;
	protected $retriever;

	public function __construct($cache, $retriever)
	{
		$this->cache = $cache;
		$this->retriever = $retriever;
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function fetch($path)
	{
		$json = $this->cache->retrieve($path);

		if (!$json)
		{
			$retriever = $this->retriever;
			$json = $retriever($path);

			if ($json)
			{
				$this->cache->store($path, $json);
			}
		}

		return json_decode($json, true);
	}

	public function __invoke($path)
	{
		return $this->fetch($path);
	}
}