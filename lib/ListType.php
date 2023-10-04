<?php

namespace ICanBoogie\CLDR;

/**
 * @see: https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#11-list-patterns
 */
enum ListType: string
{
	case STANDARD = 'standard';
	case STANDARD_SHORT = 'standard-short';
	case STANDARD_NARROW = 'standard-narrow';
	case OR = 'or';
	case OR_SHORT = 'or-short';
	case OR_NARROW = 'or-narrow';
	case UNIT = 'unit';
	case UNIT_SHORT = 'unit-short';
	case UNIT_NARROW = 'unit-narrow';
}
