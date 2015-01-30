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

/**
 * Retrieves sections from the CLDR source.
 */
class WebProvider implements ProviderInterface
{
	private $origin;
	private $connection;

	/**
	 * Initializes the {@link $origin} property.
	 *
	 * @param string $origin
	 */
	public function __construct($origin="http://www.unicode.org/repos/cldr-aux/json/26/")
	{
		$this->origin = $origin;
	}

	/**
	 * Returns a reusable cURL connection.
	 *
	 * @return resource
	 */
	private function obtain_connection()
	{
		if ($this->connection)
		{
			return $this->connection;
		}

		$connection = curl_init();

		curl_setopt_array($connection, [

			CURLOPT_FAILONERROR => true,
			CURLOPT_RETURNTRANSFER => 1

		]);

		return $this->connection = $connection;
	}

	/**
	 * The section path, following the pattern "<identity>/<section>".
	 *
	 * @param string $path
	 *
	 * @throws ResourceNotFound when the specified path does not exists on the CLDR source.
	 *
	 * @return string
	 */
	public function provide($path)
	{
		$connection = $this->obtain_connection();

		curl_setopt($connection, CURLOPT_URL, $this->origin . $path . '.json');

		$rc = curl_exec($connection);

		$http_code = curl_getinfo($connection, CURLINFO_HTTP_CODE);

		if ($http_code != 200)
		{
			throw new ResourceNotFound($path);
		}

		return json_decode($rc, true);
	}
}
