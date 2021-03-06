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

use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\ResourceNotFound;
use ICanBoogie\CLDR\Provider\WebProvider\PathMapper;

/**
 * Retrieves sections from the CLDR source using cURL.
 */
final class WebProvider implements Provider
{
	/**
	 * @var resource
	 */
	private $connection;

	/**
	 * @var PathMapper
	 */
	private $mapper;

	/**
	 * @param string $origin
	 * @param string $version
	 * @param string $variation
	 */
	public function __construct(
		$origin = PathMapper::DEFAULT_ORIGIN,
		$version = PathMapper::DEFAULT_VERSION,
		$variation = PathMapper::DEFAULT_VARIATION
	) {
		$this->mapper = new PathMapper($origin, $version, $variation);
	}

	/**
	 * @inheritdoc
	 *
	 * @throws ResourceNotFound when the specified path does not exists on the CLDR source.
	 */
	public function provide($key)
	{
		$connection = $this->obtain_connection();
		$url = $this->map($key);

		curl_setopt($connection, CURLOPT_URL, $url);

		$rc = curl_exec($connection);

		$http_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);

		if ($http_code != 200)
		{
			throw new ResourceNotFound($key);
		}

		return json_decode($rc, true);
	}

	/**
	 * Returns a reusable cURL connection.
	 *
	 * @return resource
	 */
	private function obtain_connection()
	{
		$connection = &$this->connection;

		return $connection ?: $connection = $this->create_connection();
	}

	/**
	 * @return resource
	 */
	private function create_connection()
	{
		$connection = curl_init();

		curl_setopt_array($connection, [

			CURLOPT_FAILONERROR => true,
			CURLOPT_RETURNTRANSFER => true

		]);

		return $connection;
	}

	/**
	 * Map a CLDR path to a distribution URL.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	private function map($path)
	{
		return $this->mapper->map($path);
	}
}
