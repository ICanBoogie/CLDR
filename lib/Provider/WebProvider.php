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

use CurlHandle;
use ICanBoogie\CLDR\GitHub\UrlResolver;
use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\ResourceNotFound;

use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function curl_setopt_array;
use function is_string;
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
	private CurlHandle $connection;

	public function __construct(
		private readonly UrlResolver $url_resolver = new UrlResolver()
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function provide(string $path): array
	{
		$connection = $this->obtain_connection();
		$url = $this->url_resolver->resolve($path);

		curl_setopt($connection, CURLOPT_URL, $url);

		$rc = curl_exec($connection);

		$http_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);

		if ($http_code != 200)
		{
			throw new ResourceNotFound("Unable to fetch '$path', 'GET $url' responds with $http_code");
		}

		assert(is_string($rc));

		return json_decode($rc, true);
	}

	/**
	 * Returns a reusable cURL connection.
	 */
	private function obtain_connection(): CurlHandle
	{
		return $this->connection ??= $this->create_connection();
	}

	private function create_connection(): CurlHandle
	{
		$connection = curl_init();

		curl_setopt_array($connection, [

			CURLOPT_FAILONERROR => true,
			CURLOPT_RETURNTRANSFER => true

		]);

		return $connection;
	}
}
