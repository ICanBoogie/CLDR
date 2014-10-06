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
 * <pre>
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $datetime = '2013-11-05 21:22:23';
 *
 * $formatter = new TimeFormatter($repository->locales['en']);
 *
 * echo $formatter($datetime, 'full');   // 9:22:23 PM CET
 * echo $formatter($datetime, 'long');   // 9:22:23 PM CET
 * echo $formatter($datetime, 'medium'); // 9:22:23 PM
 * echo $formatter($datetime, 'short');  // 9:22 PM
 *
 * $formatter = new TimeFormatter($repository->locales['fr']);
 *
 * echo $formatter($datetime, 'full');   // 21:22:23 CET
 * echo $formatter($datetime, 'long');   // 21:22:23 CET
 * echo $formatter($datetime, 'medium'); // 21:22:23
 * echo $formatter($datetime, 'short');  // 21:22
 * </pre>
 */
class TimeFormatter extends DateTimeFormatter
{
	/**
	 * Resolves widths defined in `timeFormats` (full, long, medium, short) into a pattern.
	 */
	protected function resolve_pattern($pattern_or_width_or_skeleton)
	{
		static $widths = array('full', 'long', 'medium', 'short');

		if (in_array($pattern_or_width_or_skeleton, $widths))
		{
			return $this->calendar['timeFormats'][$pattern_or_width_or_skeleton];
		}

		return parent::resolve_pattern($pattern_or_width_or_skeleton);
	}
}