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

use function is_array;

/**
 * Representation of a calendar collection.
 *
 * <pre>
 * <?php
 *
 * $calendar_collection = $repository->locales['fr']->calendars;
 * $gregorian_calendar = $calendar_collection['gregorian'];
 * </pre>
 *
 * @extends AbstractCollection<Calendar>
 */
final class CalendarCollection extends AbstractCollection
{
	public function __construct(
		public readonly Locale $locale
	) {
		parent::__construct(function (string $id): Calendar {

			$data = $this->locale["ca-$id"];

			assert(is_array($data));

			return new Calendar($this->locale, $data);

		});
	}
}
