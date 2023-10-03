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
final class RelationCache
{
	/**
	 * @var array<string, Relation>
	 *     Where _key_ is a relation statement and _value_ a {@link Relation}.
	 */
	static private array $instances = [];

	/**
	 * @param callable():Relation $new
	 */
	static public function get(string $relation, callable $new): Relation
	{
		return self::$instances[$relation] ??= $new();
	}
}
