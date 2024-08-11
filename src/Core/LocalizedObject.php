<?php

namespace ICanBoogie\CLDR\Core;

/**
 * Representation of a localized object.
 *
 * @template T of object
 */
abstract class LocalizedObject
{
    /**
     * @phpstan-param T $target The object to localize.
     */
    public function __construct(
        public readonly object $target,
        public readonly Locale $locale,
    ) {
    }
}
