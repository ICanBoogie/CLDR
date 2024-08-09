<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Representation of a localized object.
 *
 * @template T of object
 */
abstract class LocalizedObject
{
    /**
     * @uses get_target
     * @uses get_locale
     */
    use AccessorTrait;

    /**
     * @phpstan-param T $target The object to localize.
     */
    public function __construct(
        public readonly object $target,
        public readonly Locale $locale,
    ) {
    }
}
