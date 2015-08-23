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

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Exception throw when a path does not exists on the CLDR source.
 *
 * @property-read string $path The path.
 */
class ResourceNotFound extends \Exception implements Exception
{
	use AccessorTrait;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @return string
	 */
	protected function get_path()
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 * @param int $code
	 * @param \Exception|null $previous
	 */
	public function __construct($path, $code = 500, \Exception $previous = null)
	{
		$this->path = $path;

		parent::__construct("Path not defined: $path.", $code, $previous);
	}
}
