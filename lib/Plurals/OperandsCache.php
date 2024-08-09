<?php

namespace ICanBoogie\CLDR\Plurals;

/**
 * @internal
 */
final class OperandsCache
{
    /**
     * @param float|int|numeric-string $number
     * @param callable():Operands $new
     */
    public static function get(float|int|string $number, callable $new): Operands
    {
        static $instances;

        $key = "number-$number";

        return $instances[$key] ??= $new();
    }
}
