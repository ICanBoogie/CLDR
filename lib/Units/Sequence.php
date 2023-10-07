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
use ICanBoogie\CLDR\UnitLength;
use ICanBoogie\CLDR\Units;

/**
 * Representation of a unit/number sequence.
 *
 * @internal
 *
 * @property-read string $as_long Long string representation.
 * @property-read string $as_short Short string representation.
 * @property-read string $as_narrow Narrow string representation.
 *
 * @see http://unicode.org/reports/tr35/tr35-general.html#Unit_Sequences
 */
final class Sequence
{
    use SequenceCompanion;

	/**
	 * @uses get_as_long
	 * @uses get_as_short
	 * @uses get_as_narrow
	 */
	use AccessorTrait;

	/**
	 * @var array<string, int>
	 */
	private array $sequence = [];

	public function __construct(
		private readonly Units $units
	) {
	}

	public function __toString(): string
	{
		return $this->format();
	}

	private function get_as_long(): string
	{
		return $this->format(UnitLength::LONG);
	}

	private function get_as_short(): string
	{
		return $this->format(UnitLength::SHORT);
	}

	private function get_as_narrow(): string
	{
		return $this->format(UnitLength::NARROW);
	}

	/**
	 * Formats the sequence.
	 */
	public function format(UnitLength $length = Units::DEFAULT_LENGTH): string
	{
		return $this->units->format_sequence($this->sequence, $length);
	}
}
