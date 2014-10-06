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
 * Retrives sections from the CLDR source.
 */
class Retriever
{
	protected $origin = 'http://www.unicode.org/repos/cldr-aux/json/24/';

	/**
	 * Initializes the {@link $origin} property if the `$origin` param is specified.
	 *
	 * @param string $origin
	 */
	public function __construct($origin=null)
	{
		if ($origin)
		{
			$this->origin = $origin;
		}
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
	public function __invoke($path)
	{
		$ch = curl_init($this->origin . $path . '.json');

		curl_setopt_array
		(
			$ch, array
			(
				CURLOPT_FAILONERROR => true,
				CURLOPT_RETURNTRANSFER => 1
			)
		);

		$rc = curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if ($http_code != 200)
		{
			throw new ResourceNotFound($path);
		}

		return $rc;
	}
}