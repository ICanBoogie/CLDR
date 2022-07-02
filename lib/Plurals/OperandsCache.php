<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Plurals;

/**
 * @internal
 */
final class OperandsCache
{
	/**
	 * @var array<string, Operands>
	 */
	static private $instances = [];

	/**
	 * @param numeric $number
	 * @param callable():Operands $new
	 */
	static public function get($number, callable $new): Operands
	{
		$key = "number-$number";

		if (isset(self::$instances[$key]))
		{
			return self::$instances[$key];
		}

		return self::$instances[$key] = $new();
	}
}
