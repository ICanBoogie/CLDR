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
use Throwable;

/**
 * Exception throw when a path does not exist on the CLDR source.
 *
 * @property-read string $path The path.
 */
final class ResourceNotFound extends \Exception implements Exception
{
	/**
	 * @uses get_path
	 */
	use AccessorTrait;

	/**
	 * @var string
	 */
	private $path;

	private function get_path(): string
	{
		return $this->path;
	}

	public function __construct(string $path, int $code = 500, Throwable $previous = null)
	{
		$this->path = $path;

		parent::__construct("Path not defined: $path.", $code, $previous);
	}
}
