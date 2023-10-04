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
 * Representation of a unit.
 *
 * @property-read string $name
 * @uses self::get_name()
 * @property-read string $long_name
 * @uses self::get_long_name()
 * @property-read string $short_name
 * @uses self::get_short_name()
 * @property-read string $narrow_name
 * @uses self::get_narrow_name()
 */
final class Unit
{
	/**
	 * @uses get_name
	 * @uses get_long_name
	 * @uses get_short_name
	 * @uses get_narrow_name
	 */
	use AccessorTrait;

	private function get_name(): string
	{
		return $this->long_name;
	}

	private function get_long_name(): string
	{
		return $this->name_for(UnitLength::LONG);
	}

	private function get_short_name(): string
	{
		return $this->name_for(UnitLength::SHORT);
	}

	private function get_narrow_name(): string
	{
		return $this->name_for(UnitLength::NARROW);
	}

	public function __construct(
		private readonly Units $units,
		private readonly string $unit
	) {
	}

	public function __toString(): string
	{
		return $this->unit;
	}

	private function name_for(UnitLength $length): string
	{
		return $this->units->name_for($this->unit, $length);
	}
}
