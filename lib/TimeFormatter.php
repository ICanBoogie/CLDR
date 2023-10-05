<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
 * $formatter = new TimeFormatter($repository->locales['en']);
 *
 * echo $formatter($datetime, DateTimeFormatLength::FULL);   // 9:22:23 PM CET
 * echo $formatter($datetime, DateTimeFormatLength::LONG);   // 9:22:23 PM CET
 * echo $formatter($datetime, DateTimeFormatLength::MEDIUM); // 9:22:23 PM
 * echo $formatter($datetime, DateTimeFormatLength::SHORT);  // 9:22 PM
 *
 * $formatter = new TimeFormatter($repository->locales['fr']);
 *
 * echo $formatter($datetime, DateTimeFormatLength::FULL);   // 21:22:23 CET
 * echo $formatter($datetime, DateTimeFormatLength::LONG);   // 21:22:23 CET
 * echo $formatter($datetime, DateTimeFormatLength::MEDIUM); // 21:22:23
 * echo $formatter($datetime, DateTimeFormatLength::SHORT);  // 21:22
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
