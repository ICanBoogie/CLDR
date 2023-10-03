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

use function substr;

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
 *
 * @method string format_as_full() format_as_full() Formats the instance according to the `full` datetime pattern.
 * @method string format_as_long() format_as_long() Formats the instance according to the `long` datetime pattern.
 * @method string format_as_medium() format_as_medium() Formats the instance according to the `medium` datetime pattern.
 * @method string format_as_short() format_as_short() Formats the instance according to the `short` datetime pattern.
 */
final class LocalizedDateTime extends LocalizedObjectWithFormatter
{
	/**
	 * @var string[]
	 */
	static private array $format_widths = [ 'full', 'long', 'medium', 'short' ];

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
		$width = substr($property, 3);

		if (str_starts_with($property, 'as_') && in_array($width, self::$format_widths))
		{
			return $this->{ 'format_as_' . $width }();
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
		if (str_starts_with($method, 'format_as_') && in_array($width = substr($method, 10), self::$format_widths))
		{
			return $this->format($width);
		}

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
	public function format(string $pattern): string
	{
		return $this->formatter->format($this->target, $pattern);
	}
}
