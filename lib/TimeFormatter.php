<?php

namespace ICanBoogie\CLDR;

/**
 * Time formatter.
 *
 * ```php
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $datetime = '2013-11-05 21:22:23';
 *
 * $format = new TimeFormatter($repository->locales['en'])->format(...);
 *
 * echo $format($datetime, DateTimeFormatLength::FULL);   // 9:22:23 PM CET
 * echo $format($datetime, DateTimeFormatLength::LONG);   // 9:22:23 PM CET
 * echo $format($datetime, DateTimeFormatLength::MEDIUM); // 9:22:23 PM
 * echo $format($datetime, DateTimeFormatLength::SHORT);  // 9:22 PM
 *
 * $format = new TimeFormatter($repository->locales['fr'])->format(...);
 *
 * echo $format($datetime, DateTimeFormatLength::FULL);   // 21:22:23 CET
 * echo $format($datetime, DateTimeFormatLength::LONG);   // 21:22:23 CET
 * echo $format($datetime, DateTimeFormatLength::MEDIUM); // 21:22:23
 * echo $format($datetime, DateTimeFormatLength::SHORT);  // 21:22
 * ```
 */
final class TimeFormatter extends DateTimeFormatter
{
	/**
	 * Resolves length defined in `timeFormats` into a pattern.
	 */
	protected function resolve_pattern(
		string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
	): string {
		if ($pattern_or_length_or_id instanceof DateTimeFormatLength) {
			return $this->calendar['timeFormats'][$pattern_or_length_or_id->value];
		}

		return parent::resolve_pattern($pattern_or_length_or_id);
	}
}
