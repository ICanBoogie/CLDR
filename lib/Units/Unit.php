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
class Unit
{
	use AccessorTrait;

	/**
	 * @var Units
	 */
	private $units;

	/**
	 * @var string
	 */
	private $unit;

	/**
	 * @return string
	 */
	protected function get_name()
	{
		return $this->long_name;
	}

	/**
	 * @return string
	 */
	protected function get_long_name()
	{
		return $this->name_for(Units::LENGTH_LONG);
	}

	/**
	 * @return string
	 */
	protected function get_short_name()
	{
		return $this->name_for(Units::LENGTH_SHORT);
	}

	/**
	 * @return string
	 */
	protected function get_narrow_name()
	{
		return $this->name_for(Units::LENGTH_NARROW);
	}

	/**
	 * @param Units $units
	 * @param string $unit
	 */
	public function __construct(Units $units, $unit)
	{
		$this->units = $units;
		$this->unit = $unit;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->unit;
	}

	/**
	 * @param number|string $number
	 * @param Unit|string $unit
	 * @param string $length
	 *
	 * @return string
	 */
	public function per_unit($number, $unit, $length = Units::DEFAULT_LENGTH)
	{
		return $this->units->format_combination($number, (string) $this, (string) $unit, $length);
	}

	/**
	 * @param string $length One of `Units::LENGTH_*`.
	 *
	 * @return string
	 */
	private function name_for($length)
	{
		return $this->units->name_for($this->unit, $length);
	}
}
