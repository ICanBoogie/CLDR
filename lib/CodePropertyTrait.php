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
 * A trait for classes implementing the `code` property.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read string $code
 */
trait CodePropertyTrait
{
	/**
	 * @var string
	 */
	private $code;

	protected function get_code()
	{
		return $this->code;
	}

	public function __toString()
	{
		return $this->code;
	}
}
