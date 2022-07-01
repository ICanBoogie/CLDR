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
 * Representation of a unit.
 *
 * @property-read string $name
 * @property-read string $long_name
 * @property-read string $short_name
 * @property-read string $narrow_name
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

	/**
	 * @var Units
	 */
	private $units;

	/**
	 * @var string
	 */
	private $unit;

	private function get_name(): string
	{
		return $this->long_name;
	}

	private function get_long_name(): string
	{
		return $this->name_for(Units::LENGTH_LONG);
	}

	private function get_short_name(): string
	{
		return $this->name_for(Units::LENGTH_SHORT);
	}

	private function get_narrow_name(): string
	{
		return $this->name_for(Units::LENGTH_NARROW);
	}

	public function __construct(Units $units, string $unit)
	{
		$this->units = $units;
		$this->unit = $unit;
	}

	public function __toString(): string
	{
		return $this->unit;
	}

	/**
	 * @param Units::LENGTH_* $length
	 */
	private function name_for(string $length): string
	{
		return $this->units->name_for($this->unit, $length);
	}
}
