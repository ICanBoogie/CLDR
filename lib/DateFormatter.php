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
 * Date formatter.
 *
 * <pre>
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $datetime = '2013-11-05 21:22:23';
 *
 * $formatter = new DateFormatter($repository->locales['en']);
 *
 * echo $formatter($datetime, 'full');   // Tuesday, November 5, 2013
 * echo $formatter($datetime, 'long');   // November 5, 2013
 * echo $formatter($datetime, 'medium'); // Nov 5, 2013
 * echo $formatter($datetime, 'short');  // 11/5/13
 *
 * $formatter = new DateFormatter($repository->locales['fr']);
 *
 * echo $formatter($datetime, 'full');   // mardi 5 novembre 2013
 * echo $formatter($datetime, 'long');   // 5 novembre 2013
 * echo $formatter($datetime, 'medium'); // 5 nov. 2013
 * echo $formatter($datetime, 'short');  // 05/11/2013
 * </pre>
 */
final class DateFormatter extends DateTimeFormatter
{
	/**
	 * Resolves length defined in `dateFormats` into a pattern.
	 */
	protected function resolve_pattern(
		string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
	): string {
		if ($pattern_or_length_or_id instanceof DateTimeFormatLength) {
			return $this->calendar['dateFormats'][$pattern_or_length_or_id->value];
		}

		return parent::resolve_pattern($pattern_or_length_or_id);
	}
}
