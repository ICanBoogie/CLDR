<?php

namespace ICanBoogie\CLDR\Supplemental\Plurals;

/**
 * @internal
 */
final class RuleCache
{
    /**
     * @param callable():Rule $new
     */
    public static function get(string $rule, callable $new): Rule
    {
        static $instances;

        return $instances[$rule] ??= $new();
    }
}
