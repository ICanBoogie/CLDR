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

use ICanBoogie\PropertyNotDefined;

/**
 * Exception throw when a path does not exists on the CLDR source.
 *
 * @property-read string $path The path.
 */
class ResourceNotFound extends \Exception
{
	private $path;

	public function __construct($path, $code=500, \Exception $previous=null)
	{
		$this->path = $path;

		parent::__construct("Path not defined: $path.", $code, $previous);
	}

	public function __get($property)
	{
		if ($property == 'path')
		{
			return $this->path;
		}

		throw new PropertyNotDefined([ $property, $this ]);
	}
}
