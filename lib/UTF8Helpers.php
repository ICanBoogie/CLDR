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

use function preg_replace;

final class UTF8Helpers
{
	static public function trim(string $string): string
	{
		return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $string);
	}
}
