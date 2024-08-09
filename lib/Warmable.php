<?php

namespace ICanBoogie\CLDR;

use Closure;

/**
 * An interface for components that can warm the CLDR cache.
 */
interface Warmable
{
    /**
     * @param Closure(string $progress):void $progress
     */
    public function warm_up(Closure $progress): void;
}
