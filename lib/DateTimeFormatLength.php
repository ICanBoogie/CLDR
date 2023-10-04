<?php

namespace ICanBoogie\CLDR;

/**
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-dates.html#26-element-datetimeformats
 */
enum DateTimeFormatLength: string
{
	case FULL = 'full';
	case LONG = 'long';
	case MEDIUM = 'medium';
	case SHORT = 'short';
}
