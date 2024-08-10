<?php

namespace ICanBoogie\CLDR;

/**
 * A localized object with a formatter.
 *
 * @template TTarget of object
 * @template TFormatter of Formatter
 *
 * @extends LocalizedObject<TTarget>
 *
 * @property-read TFormatter $formatter The formatter used to format the target object.
 *
 * @method string format() Formats the instance's target. Although the method is not
 * defined by the class, it should be implemented by subclasses.
 */
abstract class LocalizedObjectWithFormatter extends LocalizedObject
{
    /**
     * @phpstan-param TFormatter $formatter
     */
    public function __construct(
        object $target,
        Locale $locale,
        public readonly Formatter $formatter,
    ) {
        parent::__construct($target, $locale);
    }
}
