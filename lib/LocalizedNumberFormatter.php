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
 * Formats numbers using locale conventions.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read NumberFormatter $target
 */
class LocalizedNumberFormatter extends LocalizedObject
{
	/**
	 * Formats a number.
	 *
	 * @param number $number The number to format.
	 * @param null $pattern
	 * @param array $symbols Symbols used to format the number.
	 *
	 * @return string
	 */
	public function __invoke($number, $pattern=null, array $symbols=[])
	{
		return $this->format($number, $pattern, $symbols);
	}

	/**
	 * Formats a number.
	 *
	 * @param number $number The number to format.
	 * @param string|null $pattern
	 * @param array $symbols Symbols used to format the number.
	 *
	 * @return string
	 */
	public function format($number, $pattern=null, array $symbols=[])
	{
		$numbers = $this->locale->numbers;

		return $this->target->format($number, $pattern ?: $numbers->decimal_format, $symbols + $numbers->symbols);
	}
}
