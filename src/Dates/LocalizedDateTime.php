<?php

namespace ICanBoogie\CLDR\Dates;

use DateTimeInterface;
use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\LocalizedObjectWithFormatter;

/**
 * A localized date time.
 *
 * <pre>
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $ldt = new LocalizedDateTime(new \DateTime('2013-11-04 20:21:22 UTC'), $repository->locales['fr']);
 *
 * echo $ldt->as_full;          // lundi 4 novembre 2013 20:21:22 UTC
 * # or
 * echo $ldt->format_as_full(); // lundi 4 novembre 2013 20:21:22 UTC
 *
 * echo $ldt->as_long;          // 4 novembre 2013 20:21:22 UTC
 * echo $ldt->as_medium;        // 4 nov. 2013 20:21:22
 * echo $ldt->as_short;         // 04/11/2013 20:21
 * </pre>
 *
 * @extends LocalizedObjectWithFormatter<DateTimeInterface, DateTimeFormatter>
 *
 * @property-read string $as_full
 * @property-read string $as_long
 * @property-read string $as_medium
 * @property-read string $as_short
 */
final class LocalizedDateTime extends LocalizedObjectWithFormatter
{
    public function __construct(DateTimeInterface $target, Locale $locale)
    {
        parent::__construct($target, $locale, $locale->calendar->datetime_formatter);
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return match ($property) {
            'as_full' => $this->format_as_full(),
            'as_long' => $this->format_as_long(),
            'as_medium' => $this->format_as_medium(),
            'as_short' => $this->format_as_short(),
            default => $this->target->$property,
        };
    }

    public function __toString(): string
    {
        // `ATOM` is used instead of `ISO8601` because of a bug in the pattern
        // @link https://php.net/manual/en/class.datetime.php#datetime.constants.iso8601

        return $this->target->format(DateTimeInterface::ATOM);
    }

    /**
     * @see DateTimeFormatter::format()
     *
     * @throws \Exception
     */
    public function format(string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id): string
    {
        return $this->formatter->format($this->target, $pattern_or_length_or_id);
    }

    /**
     * Formats the instance according to the {@see DateTimeFormatLength::FULL} length.
     */
    public function format_as_full(): string
    {
        return $this->format(DateTimeFormatLength::FULL);
    }

    /**
     * Formats the instance according to the {@see DateTimeFormatLength::LONG} length.
     */
    public function format_as_long(): string
    {
        return $this->format(DateTimeFormatLength::LONG);
    }

    /**
     * Formats the instance according to the {@see DateTimeFormatLength::MEDIUM} length.
     */
    public function format_as_medium(): string
    {
        return $this->format(DateTimeFormatLength::MEDIUM);
    }

    /**
     * Formats the instance according to the {@see DateTimeFormatLength::SHORT} length.
     */
    public function format_as_short(): string
    {
        return $this->format(DateTimeFormatLength::SHORT);
    }
}
