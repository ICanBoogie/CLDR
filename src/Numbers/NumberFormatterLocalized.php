<?php

namespace ICanBoogie\CLDR\Numbers;

use ICanBoogie\CLDR\Core\Formatter;
use ICanBoogie\CLDR\Core\LocalizedObject;

/**
 * Formats numbers using locale conventions.
 *
 * @extends LocalizedObject<NumberFormatter>
 */
class NumberFormatterLocalized extends LocalizedObject implements Formatter
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
