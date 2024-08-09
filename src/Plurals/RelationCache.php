<?php

namespace ICanBoogie\CLDR\Plurals;

/**
 * @internal
 */
final class RelationCache
{
    /**
     * @param callable():Relation $new
     */
    public static function get(string $relation, callable $new): Relation
    {
        static $instances;

        return $instances[$relation] ??= $new();
    }
}
