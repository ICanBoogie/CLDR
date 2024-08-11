<?php

namespace ICanBoogie\CLDR\Dates;

/**
 *
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-dates.html#26-element-datetimeformats
 */
final class DateTimeFormatId
{
    public static function from(string $id): self
    {
        static $instances;

        return $instances[$id] ??= new self($id);
    }

    private function __construct(
        public readonly string $id,
    ) {
    }
}
