<?php

namespace ICanBoogie\CLDR\Plurals;

/**
 * @internal
 */
final class OperandsCache
{
	/**
	 * @var array<string, Operands>
	 */
	static private array $instances = [];

	/**
	 * @param float|int|numeric-string $number
	 * @param callable():Operands $new
	 */
	static public function get(float|int|string $number, callable $new): Operands
	{
		$key = "number-$number";

		return self::$instances[$key] ??= $new();
	}
}
