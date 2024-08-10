<?php

namespace ICanBoogie\CLDR;

use DateTimeInterface;
use LogicException;

/**
 * @property-read int $timestamp Unix timestamp.
 * @property-read int $year Year.
 * @property-read int<1, 12> $month Month of the year.
 * @property-read int<1, 31> $day Day of the month.
 * @property-read int<0, 23> $hour Hour of the day.
 * @property-read int<1, 59> $minute Minute of the hour.
 * @property-read int<1, 59> $second Second of the minute.
 * @property-read int<1, 4> $quarter Quarter of the year.
 * @property-read int<1, 53> $week Week of the year.
 * @property-read int<1, 7> $weekday Day of the week.
 * @property-read int $year_day Day of the year.
 */
class DateTimeAccessor
{
    public function __construct(
        public readonly DateTimeInterface $delegate
    ) {
    }

    public function __get(string $property): int
    {
        $f = $this->delegate->format(...);

        return match ($property) {
            'year' => (int)$f('Y'),
            'month' => (int)$f('m'),
            'day' => (int)$f('d'),
            'hour' => (int)$f('H'),
            'minute' => (int)$f('i'),
            'second' => (int)$f('s'),
            'quarter' => (int)floor(($this->month - 1) / 3) + 1,
            'week' => (int)$f('W'),
            'year_day' => (int)$f('z') + 1,
            'weekday' => (int)$f('w') ?: 7,
            default => throw new LogicException("Undefined property: $property"),
        };
    }

    /**
     * @see DateTimeInterface::format
     */
    public function format(string $pattern): string
    {
        return $this->delegate->format($pattern);
    }
}
