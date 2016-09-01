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
 * @property-read int $timestamp Unix timestamp.
 * @property-read int $year Year.
 * @property-read int $month Month of the year.
 * @property-read int $day Day of the month.
 * @property-read int $hour Hour of the day.
 * @property-read int $minute Minute of the hour.
 * @property-read int $second Second of the minute.
 * @property-read int $quarter Quarter of the year.
 * @property-read int $week Week of the year.
 * @property-read int $weekday Day of the week.
 * @property-read int $year_day Day of the year.
 *
 * @method string format(string $format)
 *
 * @internal
 */
class DateTimeAccessor
{
	/**
	 * @var \DateTime
	 */
	private $datetime;

	/**
	 * @param \DateTime $datetime
	 */
	public function __construct(\DateTime $datetime)
	{
		$this->datetime = $datetime;
	}

	/**
	 * @inheritdoc
	 */
	public function __get($property)
	{
		$dt = $this->datetime;

		switch ($property)
		{
			case 'year':
				return (int) $dt->format('Y');
			case 'month':
				return (int) $dt->format('m');
			case 'day':
				return (int) $dt->format('d');
			case 'hour':
				return (int) $dt->format('H');
			case 'minute':
				return (int) $dt->format('i');
			case 'second':
				return (int) $dt->format('s');
			case 'quarter':
				return floor(($this->month - 1) / 3) + 1;
			case 'week':
				return (int) $dt->format('W');
			case 'year_day':
				return (int) $dt->format('z') + 1;
			case 'weekday':
				return (int) $dt->format('w') ?: 7;
		}

		throw new \LogicException("Undefined property: $property");
	}

	/**
	 * @inheritdoc
	 */
	public function __call($name, $params)
	{
		return call_user_func_array([ $this->datetime, $name ], $params);
	}
}
