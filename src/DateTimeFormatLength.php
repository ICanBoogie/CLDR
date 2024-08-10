<?php

namespace ICanBoogie\CLDR;

/**
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-dates.html#26-element-datetimeformats
 */
enum DateTimeFormatLength: string
{
    case FULL = 'full';
    case LONG = 'long';
    case MEDIUM = 'medium';
    case SHORT = 'short';
}
