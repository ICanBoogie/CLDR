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
 * $gregorian_callendar = $calendar_collection['gregorian'];
 * </pre>
 */
class CalendarCollection implements \ArrayAccess
{
	/**
	 * Representation of a locale.
	 *
	 * @var Locale
	 */
	protected $repository;

	/**
	 * Calendar instances.
	 *
	 * @var Calendar[]
	 */
	protected $collection = array();

	/**
	 * Initialiazes the {@link $repository} property.
	 *
	 * @param Repository $repository Representation of a CLDR.
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
		throw new OffsetNotWritable(array($offset, $this));
	}

	public function offsetUnset($offset)
	{
		throw new OffsetNotWritable(array($offset, $this));
	}
}