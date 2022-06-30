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
use ICanBoogie\CLDR\Provider\WebProvider\PathMapper;
use ICanBoogie\CLDR\ResourceNotFound;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function curl_setopt_array;
use function json_decode;
use const CURLINFO_HTTP_CODE;
use const CURLOPT_FAILONERROR;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_URL;

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

	public function __construct(
		string $origin = PathMapper::DEFAULT_ORIGIN,
		string $version = PathMapper::DEFAULT_VERSION,
		string $variation = PathMapper::DEFAULT_VARIATION
	) {
		$this->mapper = new PathMapper($origin, $version, $variation);
	}

	/**
	 * @inheritDoc
	 */
	public function provide(string $path): array
	{
		$connection = $this->obtain_connection();
		$url = $this->map($path);

		curl_setopt($connection, CURLOPT_URL, $url);

		$rc = curl_exec($connection);

		$http_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);

		if ($http_code != 200)
		{
			throw new ResourceNotFound($path);
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

		return $connection ?: $connection = $this->create_connection(); // @phpstan-ignore-line
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
	 */
	private function map(string $path): string
	{
		return $this->mapper->map($path);
	}
}
