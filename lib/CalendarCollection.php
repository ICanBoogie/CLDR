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

use ICanBoogie\Accessor\AccessorTrait;

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
 * @method Calendar offsetGet(string $id)
 */
final class CalendarCollection extends AbstractCollection
{
	/**
	 * @uses get_locale
	 */
	use AccessorTrait;
	use LocalePropertyTrait;

	public function __construct(Locale $locale)
	{
		$this->locale = $locale;

		parent::__construct(function ($id): Calendar {

			return new Calendar($this->locale, $this->locale["ca-$id"]);

		});
	}
}
