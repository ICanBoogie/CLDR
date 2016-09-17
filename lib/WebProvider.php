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

use ICanBoogie\Storage\Storage;

/**
 * Retrieves sections from the CLDR source using cURL.
 */
class WebProvider implements Storage, Provider
{
	use ProviderStorageBinding;

	const DEFAULT_ORIGIN = "http://www.unicode.org/repos/cldr-aux/json/26/";

	/**
	 * @var string
	 */
	private $origin;

	/**
	 * @var resource
	 */
	private $connection;

	/**
	 * Initializes the {@link $origin} property.
	 *
	 * @param string $origin
	 */
	public function __construct($origin = self::DEFAULT_ORIGIN)
	{
		$this->origin = $origin;
	}

	/**
	 * @inheritdoc
	 * @codeCoverageIgnore
	 */
	public function store($key, $value, $ttl = null)
	{
		# does nothing
	}

	/**
	 * The section path, following the pattern "<identity>/<section>".
	 *
	 * @param string $key
	 *
	 * @return string
	 *
	 * @throws ResourceNotFound when the specified path does not exists on the CLDR source.
	 */
	public function retrieve($key)
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
	 * The method does nothing.
	 *
	 * @inheritdoc
	 * @codeCoverageIgnore
	 */
	public function eliminate($key)
	{
		# does nothing
	}

	/**
	 * The method does nothing.
	 *
	 * @inheritdoc
	 * @codeCoverageIgnore
	 */
	public function exists($key)
	{
		# does nothing
	}

	/**
	 * The method does nothing.
	 *
	 * @inheritdoc
	 * @codeCoverageIgnore
	 */
	public function clear()
	{
		# does nothing
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator()
	{
		# does nothing
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
		return "{$this->origin}$path.json";
	}
}
