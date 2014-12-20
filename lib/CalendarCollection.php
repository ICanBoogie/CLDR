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

use ICanBoogie\OffsetNotWritable;

/**
 * Representation of a calendar collection.
 *
 * <pre>
 * <?php
 *
 * $calendar_collection = $repository->locales['fr']->calendars;
 * $gregorian_calendar = $calendar_collection['gregorian'];
 * </pre>
 */
class CalendarCollection implements \ArrayAccess
{
	use LocalePropertyTrait;

	/**
	 * Calendar instances.
	 *
	 * @var Calendar[]
	 */
	protected $collection = [];

	/**
	 * Initializes the {@link $locale} property.
	 *
	 * @param Locale $locale Locale.
	 */
	public function __construct(Locale $locale)
	{
		$this->locale = $locale;
	}

	public function offsetExists($offset)
	{
		throw new \BadMethodCallException("The method is not implemented");
	}

	public function offsetGet($offset)
	{
		if (empty($this->collection[$offset]))
		{
			$this->collection[$offset] = new Calendar($this->locale, $this->locale["ca-{$offset}"]);
		}

		return $this->collection[$offset];
	}

	public function offsetSet($offset, $value)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable([ $offset, $this ]);
	}
}
