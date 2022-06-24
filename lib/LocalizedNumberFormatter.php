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
 * @property-read NumberFormatter $target
 */
class LocalizedNumberFormatter extends LocalizedObject implements Formatter
{
	/**
	 * Formats a number.
	 *
	 * @param numeric $number The number to format.
	 */
	public function __invoke($number, string $pattern = null): string
	{
		return $this->format($number, $pattern);
	}

	/**
	 * Formats a number.
	 *
	 * @param numeric $number The number to format.
	 */
	public function format($number, string $pattern = null): string
	{
		$numbers = $this->locale->numbers;

		return $this->target->format($number, $pattern ?: $numbers->decimal_format, $numbers->symbols);
	}
}
