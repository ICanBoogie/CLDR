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

class UTF8Helpers
{
	/**
	 * @param string $string
	 *
	 * @return string
	 */
	static public function trim($string)
	{
		return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $string);
	}
}
