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
final class SamplesCache
{
	/**
	 * @var array<string, Samples>
	 *     Where _key_ is a rule statement and _value_ a {@link Samples}.
	 */
	static private array $instances = [];

	/**
	 * @param callable():Samples $new
	 */
	static public function get(string $samples, callable $new): Samples
	{
		return self::$instances[$samples] ??= $new();
	}
}
