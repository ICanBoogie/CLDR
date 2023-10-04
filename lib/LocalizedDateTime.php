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

use DateTime;
use DateTimeInterface;
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
 * @extends LocalizedObjectWithFormatter<DateTimeInterface, DateTimeFormatter>
 *
 * @property-read string $as_full
 * @property-read string $as_long
 * @property-read string $as_medium
 * @property-read string $as_short
 */
final class LocalizedDateTime extends LocalizedObjectWithFormatter
{
	/**
	 * @inheritDoc
	 *
	 * @return DateTimeFormatter
	 */
	protected function lazy_get_formatter(): Formatter
	{
		return $this->locale->calendar->datetime_formatter;
	}

	/**
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		if (str_starts_with($property, 'as_'))
		{
			return $this->{ 'format_' . $property }();
		}

		try
		{
			return parent::__get($property);
		}
		catch (PropertyNotDefined)
		{
			return $this->target->$property;
		}
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set($property, $value): void
	{
		$this->target->$property = $value;
	}

	/**
	 * @param string $method
	 * @param array<string, mixed> $arguments
	 *
	 * @return mixed
	 *
	 * @throws \Exception
	 */
	public function __call($method, $arguments)
	{
		return $this->target->$method(...$arguments);
	}

	public function __toString(): string
	{
		$target = $this->target;

		if (method_exists($target, __FUNCTION__))
		{
			return (string) $target;
		}

		// `ATOM` is used instead of `ISO8601` because of a bug in the pattern
		// @see http://php.net/manual/en/class.datetime.php#datetime.constants.iso8601

		return $this->target->format(DateTime::ATOM);
	}

	/**
	 * @inheritDoc
	 *
	 * @throws \Exception
	 */
	public function format(string|DateTimeFormatLength $pattern): string
	{
		return $this->formatter->format($this->target, $pattern);
	}

	/**
	 * Formats the instance according to the {@link DateTimeFormatLength::FULL} length.
	 */
	public function format_as_full(): string
	{
		return $this->format(DateTimeFormatLength::FULL);
	}

	/**
	 * Formats the instance according to the {@link DateTimeFormatLength::LONG} length.
	 */
	public function format_as_long(): string
	{
		return $this->format(DateTimeFormatLength::LONG);
	}

	/**
	 * Formats the instance according to the {@link DateTimeFormatLength::MEDIUM} length.
	 */
	public function format_as_medium(): string
	{
		return $this->format(DateTimeFormatLength::MEDIUM);
	}

	/**
	 * Formats the instance according to the {@link DateTimeFormatLength::SHORT} length.
	 */
	public function format_as_short(): string
	{
		return $this->format(DateTimeFormatLength::SHORT);
	}
}
