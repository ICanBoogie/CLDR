<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Units;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Units;

/**
 * @internal
 *
 * @property-read string $as_long A long format of the number.
 * @property-read string $as_short A short format of the number.
 * @property-read string $as_narrow A narrow format of the number.
 */
final class NumberPerUnit
{
	/**
	 * @uses get_as_long
	 * @uses get_as_short
	 * @uses get_as_narrow
	 */
	use AccessorTrait;

	/**
	 * @param float|int|numeric-string $number
	 */
	public function __construct(
		private readonly float|int|string $number,
		private readonly string $number_unit,
		private readonly string $per_unit,
		private readonly Units $units
	) {
	}

	public function __toString(): string
	{
		return $this->as(Units::DEFAULT_LENGTH);
	}

	private function get_as_long(): string
	{
		return $this->as(Units::LENGTH_LONG);
	}

	private function get_as_short(): string
	{
		return $this->as(Units::LENGTH_SHORT);
	}

	private function get_as_narrow(): string
	{
		return $this->as(Units::LENGTH_NARROW);
	}

	/**
	 * @param Units::LENGTH_* $length
	 */
	private function as(string $length): string
	{
		return $this->units->format_compound(
			$this->number,
			$this->number_unit,
			$this->per_unit,
			$length
		);
	}
}
