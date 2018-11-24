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
 * An interface for classes that can provide CLDR data.
 */
interface Provider
{
	/**
	 * The section path, following the pattern "<identity>/<section>".
	 *
	 * @param string $path
	 *
	 * @throws ResourceNotFound when the specified path does not exists on the CLDR source.
	 *
	 * @return array
	 */
	public function provide($path);
}
