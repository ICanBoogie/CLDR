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

use ICanBoogie\PropertyNotDefined;

/**
 * A localized date time.
 *
 * <pre>
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $ldt = new LocalizedDateTime(new \DateTime('2013-11-04 20:21:22 UTC'), $repository->locales['fr']);
 *
 * echo $ldt->as_full;          // lundi 4 novembre 2013 20:21:22 UTC
 * # or
 * echo $ldt->format_as_full(); // lundi 4 novembre 2013 20:21:22 UTC
 *
 * echo $ldt->as_long;          // 4 novembre 2013 20:21:22 UTC
 * echo $ldt->as_medium;        // 4 nov. 2013 20:21:22
 * echo $ldt->as_short;         // 04/11/2013 20:21
 * </pre>
 *
 * @property-read string $as_full
 * @property-read string $as_long
 * @property-read string $as_medium
 * @property-read string $as_short
 *
 * @method string format_as_full() format_as_full() Formats the instance according to the `full` datetime pattern.
 * @method string format_as_long() format_as_long() Formats the instance according to the `long` datetime pattern.
 * @method string format_as_medium() format_as_medium() Formats the instance according to the `medium` datetime pattern.
 * @method string format_as_short() format_as_short() Formats the instance according to the `short` datetime pattern.
 */
class LocalizedDateTime extends LocalizedObjectWithFormatter
{
	static private $format_widths = [ 'full', 'long', 'medium', 'short' ];

	/**
	 * Returns the formatter.
	 *
	 * @return DateTimeFormatter
	 */
	protected function lazy_get_formatter()
	{
		return $this->locale->calendar->datetime_formatter;
	}

	public function __get($property)
	{
		if (strpos($property, 'as_') === 0 && in_array($width = substr($property, 3), self::$format_widths))
		{
			return $this->{ 'format_as_' . $width }();
		}

		try
		{
			return parent::__get($property);
		}
		catch (PropertyNotDefined $e)
		{
			return $this->target->$property;
		}
	}

	public function __set($property, $value)
	{
		$this->target->$property = $value;
	}

	public function __call($method, $arguments)
	{
		if (strpos($method, 'format_as_') === 0 && in_array($width = substr($method, 10), self::$format_widths))
		{
			return $this->format($width);
		}

		return call_user_func_array([ $this->target, $method ], $arguments);
	}

	public function __toString()
	{
		return (string) $this->target;
	}

	/**
	 * @inheritdoc
	 *
	 * @param string|null $pattern
	 *
	 * @return string
	 */
	public function format($pattern = null)
	{
		return $this->formatter->format($this->target, $pattern);
	}
}
