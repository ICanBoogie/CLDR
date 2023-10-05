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

use DateTimeInterface;
use LogicException;

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
 */
class DateTimeAccessor
{
	public function __construct(
		private readonly DateTimeInterface $datetime
	) {
	}

	/**
	 * @return mixed
	 */
	public function __get(string $property)
	{
		$dt = $this->datetime;

		return match ($property) {
			'year' => (int)$dt->format('Y'),
			'month' => (int)$dt->format('m'),
			'day' => (int)$dt->format('d'),
			'hour' => (int)$dt->format('H'),
			'minute' => (int)$dt->format('i'),
			'second' => (int)$dt->format('s'),
			'quarter' => (int)floor(($this->month - 1) / 3) + 1,
			'week' => (int)$dt->format('W'),
			'year_day' => (int)$dt->format('z') + 1,
			'weekday' => (int)$dt->format('w') ?: 7,
			default => throw new LogicException("Undefined property: $property"),
		};
	}

	/**
	 * @param mixed[] $params
	 *
	 * @return mixed
	 */
	public function __call(string $name, array $params)
	{
		return $this->datetime->$name(...$params);
	}
}
