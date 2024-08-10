<?php

namespace ICanBoogie\CLDR;

/**
 * Formats numbers using locale conventions.
 *
 * @extends LocalizedObject<NumberFormatter>
 */
class LocalizedNumberFormatter extends LocalizedObject implements Formatter
{
    /**
     * Formats a number.
     *
     * @param float|int|numeric-string $number
     */
    public function format(float|int|string $number, string $pattern = null): string
    {
        $numbers = $this->locale->numbers;

        return $this->target->format($number, $pattern ?? $numbers->standard_decimal_format, $numbers->symbols);
    }
}
