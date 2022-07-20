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
 * Representation of a unit/number sequence.
 *
 * @internal
 *
 * @property-read string $as_long Long string representation.
 * @property-read string $as_short Short string representation.
 * @property-read string $as_narrow Narrow string representation.
 *
 * @method $this acceleration_g_force(mixed $number)
 * @method $this acceleration_meter_per_second_squared(mixed $number)
 * @method $this angle_arc_minute(mixed $number)
 * @method $this angle_arc_second(mixed $number)
 * @method $this angle_degree(mixed $number)
 * @method $this angle_radian(mixed $number)
 * @method $this area_acre(mixed $number)
 * @method $this area_hectare(mixed $number)
 * @method $this area_square_centimeter(mixed $number)
 * @method $this area_square_foot(mixed $number)
 * @method $this area_square_inch(mixed $number)
 * @method $this area_square_kilometer(mixed $number)
 * @method $this area_square_meter(mixed $number)
 * @method $this area_square_mile(mixed $number)
 * @method $this area_square_yard(mixed $number)
 * @method $this consumption_liter_per_kilometer(mixed $number)
 * @method $this consumption_mile_per_gallon(mixed $number)
 * @method $this digital_bit(mixed $number)
 * @method $this digital_byte(mixed $number)
 * @method $this digital_gigabit(mixed $number)
 * @method $this digital_gigabyte(mixed $number)
 * @method $this digital_kilobit(mixed $number)
 * @method $this digital_kilobyte(mixed $number)
 * @method $this digital_megabit(mixed $number)
 * @method $this digital_megabyte(mixed $number)
 * @method $this digital_terabit(mixed $number)
 * @method $this digital_terabyte(mixed $number)
 * @method $this duration_day(mixed $number)
 * @method $this duration_hour(mixed $number)
 * @method $this duration_microsecond(mixed $number)
 * @method $this duration_millisecond(mixed $number)
 * @method $this duration_minute(mixed $number)
 * @method $this duration_month(mixed $number)
 * @method $this duration_nanosecond(mixed $number)
 * @method $this duration_second(mixed $number)
 * @method $this duration_week(mixed $number)
 * @method $this duration_year(mixed $number)
 * @method $this electric_ampere(mixed $number)
 * @method $this electric_milliampere(mixed $number)
 * @method $this electric_ohm(mixed $number)
 * @method $this electric_volt(mixed $number)
 * @method $this energy_calorie(mixed $number)
 * @method $this energy_foodcalorie(mixed $number)
 * @method $this energy_joule(mixed $number)
 * @method $this energy_kilocalorie(mixed $number)
 * @method $this energy_kilojoule(mixed $number)
 * @method $this energy_kilowatt_hour(mixed $number)
 * @method $this frequency_gigahertz(mixed $number)
 * @method $this frequency_hertz(mixed $number)
 * @method $this frequency_kilohertz(mixed $number)
 * @method $this frequency_megahertz(mixed $number)
 * @method $this length_astronomical_unit(mixed $number)
 * @method $this length_centimeter(mixed $number)
 * @method $this length_decimeter(mixed $number)
 * @method $this length_fathom(mixed $number)
 * @method $this length_foot(mixed $number)
 * @method $this length_furlong(mixed $number)
 * @method $this length_inch(mixed $number)
 * @method $this length_kilometer(mixed $number)
 * @method $this length_light_year(mixed $number)
 * @method $this length_meter(mixed $number)
 * @method $this length_micrometer(mixed $number)
 * @method $this length_mile(mixed $number)
 * @method $this length_millimeter(mixed $number)
 * @method $this length_nanometer(mixed $number)
 * @method $this length_nautical_mile(mixed $number)
 * @method $this length_parsec(mixed $number)
 * @method $this length_picometer(mixed $number)
 * @method $this length_yard(mixed $number)
 * @method $this light_lux(mixed $number)
 * @method $this mass_carat(mixed $number)
 * @method $this mass_gram(mixed $number)
 * @method $this mass_kilogram(mixed $number)
 * @method $this mass_metric_ton(mixed $number)
 * @method $this mass_microgram(mixed $number)
 * @method $this mass_milligram(mixed $number)
 * @method $this mass_ounce(mixed $number)
 * @method $this mass_ounce_troy(mixed $number)
 * @method $this mass_pound(mixed $number)
 * @method $this mass_stone(mixed $number)
 * @method $this mass_ton(mixed $number)
 * @method $this power_gigawatt(mixed $number)
 * @method $this power_horsepower(mixed $number)
 * @method $this power_kilowatt(mixed $number)
 * @method $this power_megawatt(mixed $number)
 * @method $this power_milliwatt(mixed $number)
 * @method $this power_watt(mixed $number)
 * @method $this pressure_hectopascal(mixed $number)
 * @method $this pressure_inch_hg(mixed $number)
 * @method $this pressure_millibar(mixed $number)
 * @method $this pressure_millimeter_of_mercury(mixed $number)
 * @method $this pressure_pound_per_square_inch(mixed $number)
 * @method $this proportion_karat(mixed $number)
 * @method $this speed_kilometer_per_hour(mixed $number)
 * @method $this speed_meter_per_second(mixed $number)
 * @method $this speed_mile_per_hour(mixed $number)
 * @method $this temperature_celsius(mixed $number)
 * @method $this temperature_fahrenheit(mixed $number)
 * @method $this temperature_kelvin(mixed $number)
 * @method $this volume_acre_foot(mixed $number)
 * @method $this volume_bushel(mixed $number)
 * @method $this volume_centiliter(mixed $number)
 * @method $this volume_cubic_centimeter(mixed $number)
 * @method $this volume_cubic_foot(mixed $number)
 * @method $this volume_cubic_inch(mixed $number)
 * @method $this volume_cubic_kilometer(mixed $number)
 * @method $this volume_cubic_meter(mixed $number)
 * @method $this volume_cubic_mile(mixed $number)
 * @method $this volume_cubic_yard(mixed $number)
 * @method $this volume_cup(mixed $number)
 * @method $this volume_deciliter(mixed $number)
 * @method $this volume_fluid_ounce(mixed $number)
 * @method $this volume_gallon(mixed $number)
 * @method $this volume_hectoliter(mixed $number)
 * @method $this volume_liter(mixed $number)
 * @method $this volume_megaliter(mixed $number)
 * @method $this volume_milliliter(mixed $number)
 * @method $this volume_pint(mixed $number)
 * @method $this volume_quart(mixed $number)
 * @method $this volume_tablespoon(mixed $number)
 * @method $this volume_teaspoon(mixed $number)
 *
 * @see http://unicode.org/reports/tr35/tr35-general.html#Unit_Sequences
 */
final class Sequence
{
	/**
	 * @uses get_as_long
	 * @uses get_as_short
	 * @uses get_as_narrow
	 */
	use AccessorTrait;

	/**
	 * @var Units
	 */
	private $units;

	/**
	 * @var array<string, int>
	 */
	private $sequence = [];

	public function __construct(Units $units)
	{
		$this->units = $units;
	}

	public function __call(string $name, array $arguments): self
	{
		$unit = strtr($name, '_', '-');
		$this->units->assert_is_unit($unit);
		$this->sequence[$unit] = $arguments[0];

		return $this;
	}

	public function __toString(): string
	{
		return $this->format();
	}

	private function get_as_long(): string
	{
		return $this->format(Units::LENGTH_LONG);
	}

	private function get_as_short(): string
	{
		return $this->format(Units::LENGTH_SHORT);
	}

	private function get_as_narrow(): string
	{
		return $this->format(Units::LENGTH_NARROW);
	}

	/**
	 * Formats the sequence.
	 *
	 * @param Units::LENGTH_* $length
	 */
	public function format(string $length = Units::DEFAULT_LENGTH): string
	{
		return $this->units->format_sequence($this->sequence, $length);
	}
}
