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

use BadMethodCallException;
use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Units\Unit;
use ICanBoogie\CLDR\Units\Sequence;
use LogicException;
use function array_shift;
use function is_string;
use function str_replace;
use function strtr;

/**
 * @property-read Sequence $sequence
 *
 * @property-read Unit $acceleration_g_force
 * @property-read Unit $acceleration_meter_per_second_squared
 * @property-read Unit $angle_arc_minute
 * @property-read Unit $angle_arc_second
 * @property-read Unit $angle_degree
 * @property-read Unit $angle_radian
 * @property-read Unit $area_acre
 * @property-read Unit $area_hectare
 * @property-read Unit $area_square_centimeter
 * @property-read Unit $area_square_foot
 * @property-read Unit $area_square_inch
 * @property-read Unit $area_square_kilometer
 * @property-read Unit $area_square_meter
 * @property-read Unit $area_square_mile
 * @property-read Unit $area_square_yard
 * @property-read Unit $consumption_liter_per_kilometer
 * @property-read Unit $consumption_mile_per_gallon
 * @property-read Unit $digital_bit
 * @property-read Unit $digital_byte
 * @property-read Unit $digital_gigabit
 * @property-read Unit $digital_gigabyte
 * @property-read Unit $digital_kilobit
 * @property-read Unit $digital_kilobyte
 * @property-read Unit $digital_megabit
 * @property-read Unit $digital_megabyte
 * @property-read Unit $digital_terabit
 * @property-read Unit $digital_terabyte
 * @property-read Unit $duration_day
 * @property-read Unit $duration_hour
 * @property-read Unit $duration_microsecond
 * @property-read Unit $duration_millisecond
 * @property-read Unit $duration_minute
 * @property-read Unit $duration_month
 * @property-read Unit $duration_nanosecond
 * @property-read Unit $duration_second
 * @property-read Unit $duration_week
 * @property-read Unit $duration_year
 * @property-read Unit $electric_ampere
 * @property-read Unit $electric_milliampere
 * @property-read Unit $electric_ohm
 * @property-read Unit $electric_volt
 * @property-read Unit $energy_calorie
 * @property-read Unit $energy_foodcalorie
 * @property-read Unit $energy_joule
 * @property-read Unit $energy_kilocalorie
 * @property-read Unit $energy_kilojoule
 * @property-read Unit $energy_kilowatt_hour
 * @property-read Unit $frequency_gigahertz
 * @property-read Unit $frequency_hertz
 * @property-read Unit $frequency_kilohertz
 * @property-read Unit $frequency_megahertz
 * @property-read Unit $length_astronomical_unit
 * @property-read Unit $length_centimeter
 * @property-read Unit $length_decimeter
 * @property-read Unit $length_fathom
 * @property-read Unit $length_foot
 * @property-read Unit $length_furlong
 * @property-read Unit $length_inch
 * @property-read Unit $length_kilometer
 * @property-read Unit $length_light_year
 * @property-read Unit $length_meter
 * @property-read Unit $length_micrometer
 * @property-read Unit $length_mile
 * @property-read Unit $length_millimeter
 * @property-read Unit $length_nanometer
 * @property-read Unit $length_nautical_mile
 * @property-read Unit $length_parsec
 * @property-read Unit $length_picometer
 * @property-read Unit $length_yard
 * @property-read Unit $light_lux
 * @property-read Unit $mass_carat
 * @property-read Unit $mass_gram
 * @property-read Unit $mass_kilogram
 * @property-read Unit $mass_metric_ton
 * @property-read Unit $mass_microgram
 * @property-read Unit $mass_milligram
 * @property-read Unit $mass_ounce
 * @property-read Unit $mass_ounce_troy
 * @property-read Unit $mass_pound
 * @property-read Unit $mass_stone
 * @property-read Unit $mass_ton
 * @property-read Unit $power_gigawatt
 * @property-read Unit $power_horsepower
 * @property-read Unit $power_kilowatt
 * @property-read Unit $power_megawatt
 * @property-read Unit $power_milliwatt
 * @property-read Unit $power_watt
 * @property-read Unit $pressure_hectopascal
 * @property-read Unit $pressure_inch_hg
 * @property-read Unit $pressure_millibar
 * @property-read Unit $pressure_millimeter_of_mercury
 * @property-read Unit $pressure_pound_per_square_inch
 * @property-read Unit $proportion_karat
 * @property-read Unit $speed_kilometer_per_hour
 * @property-read Unit $speed_meter_per_second
 * @property-read Unit $speed_mile_per_hour
 * @property-read Unit $temperature_celsius
 * @property-read Unit $temperature_fahrenheit
 * @property-read Unit $temperature_kelvin
 * @property-read Unit $volume_acre_foot
 * @property-read Unit $volume_bushel
 * @property-read Unit $volume_centiliter
 * @property-read Unit $volume_cubic_centimeter
 * @property-read Unit $volume_cubic_foot
 * @property-read Unit $volume_cubic_inch
 * @property-read Unit $volume_cubic_kilometer
 * @property-read Unit $volume_cubic_meter
 * @property-read Unit $volume_cubic_mile
 * @property-read Unit $volume_cubic_yard
 * @property-read Unit $volume_cup
 * @property-read Unit $volume_deciliter
 * @property-read Unit $volume_fluid_ounce
 * @property-read Unit $volume_gallon
 * @property-read Unit $volume_hectoliter
 * @property-read Unit $volume_liter
 * @property-read Unit $volume_megaliter
 * @property-read Unit $volume_milliliter
 * @property-read Unit $volume_pint
 * @property-read Unit $volume_quart
 * @property-read Unit $volume_tablespoon
 * @property-read Unit $volume_teaspoon
 *
 * @method string acceleration_g_force(mixed $number, string $length = "long")
 * @method string acceleration_meter_per_second_squared(mixed $number, string $length = "long")
 * @method string angle_arc_minute(mixed $number, string $length = "long")
 * @method string angle_arc_second(mixed $number, string $length = "long")
 * @method string angle_degree(mixed $number, string $length = "long")
 * @method string angle_radian(mixed $number, string $length = "long")
 * @method string area_acre(mixed $number, string $length = "long")
 * @method string area_hectare(mixed $number, string $length = "long")
 * @method string area_square_centimeter(mixed $number, string $length = "long")
 * @method string area_square_foot(mixed $number, string $length = "long")
 * @method string area_square_inch(mixed $number, string $length = "long")
 * @method string area_square_kilometer(mixed $number, string $length = "long")
 * @method string area_square_meter(mixed $number, string $length = "long")
 * @method string area_square_mile(mixed $number, string $length = "long")
 * @method string area_square_yard(mixed $number, string $length = "long")
 * @method string consumption_liter_per_kilometer(mixed $number, string $length = "long")
 * @method string consumption_mile_per_gallon(mixed $number, string $length = "long")
 * @method string digital_bit(mixed $number, string $length = "long")
 * @method string digital_byte(mixed $number, string $length = "long")
 * @method string digital_gigabit(mixed $number, string $length = "long")
 * @method string digital_gigabyte(mixed $number, string $length = "long")
 * @method string digital_kilobit(mixed $number, string $length = "long")
 * @method string digital_kilobyte(mixed $number, string $length = "long")
 * @method string digital_megabit(mixed $number, string $length = "long")
 * @method string digital_megabyte(mixed $number, string $length = "long")
 * @method string digital_terabit(mixed $number, string $length = "long")
 * @method string digital_terabyte(mixed $number, string $length = "long")
 * @method string duration_day(mixed $number, string $length = "long")
 * @method string duration_hour(mixed $number, string $length = "long")
 * @method string duration_microsecond(mixed $number, string $length = "long")
 * @method string duration_millisecond(mixed $number, string $length = "long")
 * @method string duration_minute(mixed $number, string $length = "long")
 * @method string duration_month(mixed $number, string $length = "long")
 * @method string duration_nanosecond(mixed $number, string $length = "long")
 * @method string duration_second(mixed $number, string $length = "long")
 * @method string duration_week(mixed $number, string $length = "long")
 * @method string duration_year(mixed $number, string $length = "long")
 * @method string electric_ampere(mixed $number, string $length = "long")
 * @method string electric_milliampere(mixed $number, string $length = "long")
 * @method string electric_ohm(mixed $number, string $length = "long")
 * @method string electric_volt(mixed $number, string $length = "long")
 * @method string energy_calorie(mixed $number, string $length = "long")
 * @method string energy_foodcalorie(mixed $number, string $length = "long")
 * @method string energy_joule(mixed $number, string $length = "long")
 * @method string energy_kilocalorie(mixed $number, string $length = "long")
 * @method string energy_kilojoule(mixed $number, string $length = "long")
 * @method string energy_kilowatt_hour(mixed $number, string $length = "long")
 * @method string frequency_gigahertz(mixed $number, string $length = "long")
 * @method string frequency_hertz(mixed $number, string $length = "long")
 * @method string frequency_kilohertz(mixed $number, string $length = "long")
 * @method string frequency_megahertz(mixed $number, string $length = "long")
 * @method string length_astronomical_unit(mixed $number, string $length = "long")
 * @method string length_centimeter(mixed $number, string $length = "long")
 * @method string length_decimeter(mixed $number, string $length = "long")
 * @method string length_fathom(mixed $number, string $length = "long")
 * @method string length_foot(mixed $number, string $length = "long")
 * @method string length_furlong(mixed $number, string $length = "long")
 * @method string length_inch(mixed $number, string $length = "long")
 * @method string length_kilometer(mixed $number, string $length = "long")
 * @method string length_light_year(mixed $number, string $length = "long")
 * @method string length_meter(mixed $number, string $length = "long")
 * @method string length_micrometer(mixed $number, string $length = "long")
 * @method string length_mile(mixed $number, string $length = "long")
 * @method string length_millimeter(mixed $number, string $length = "long")
 * @method string length_nanometer(mixed $number, string $length = "long")
 * @method string length_nautical_mile(mixed $number, string $length = "long")
 * @method string length_parsec(mixed $number, string $length = "long")
 * @method string length_picometer(mixed $number, string $length = "long")
 * @method string length_yard(mixed $number, string $length = "long")
 * @method string light_lux(mixed $number, string $length = "long")
 * @method string mass_carat(mixed $number, string $length = "long")
 * @method string mass_gram(mixed $number, string $length = "long")
 * @method string mass_kilogram(mixed $number, string $length = "long")
 * @method string mass_metric_ton(mixed $number, string $length = "long")
 * @method string mass_microgram(mixed $number, string $length = "long")
 * @method string mass_milligram(mixed $number, string $length = "long")
 * @method string mass_ounce(mixed $number, string $length = "long")
 * @method string mass_ounce_troy(mixed $number, string $length = "long")
 * @method string mass_pound(mixed $number, string $length = "long")
 * @method string mass_stone(mixed $number, string $length = "long")
 * @method string mass_ton(mixed $number, string $length = "long")
 * @method string power_gigawatt(mixed $number, string $length = "long")
 * @method string power_horsepower(mixed $number, string $length = "long")
 * @method string power_kilowatt(mixed $number, string $length = "long")
 * @method string power_megawatt(mixed $number, string $length = "long")
 * @method string power_milliwatt(mixed $number, string $length = "long")
 * @method string power_watt(mixed $number, string $length = "long")
 * @method string pressure_hectopascal(mixed $number, string $length = "long")
 * @method string pressure_inch_hg(mixed $number, string $length = "long")
 * @method string pressure_millibar(mixed $number, string $length = "long")
 * @method string pressure_millimeter_of_mercury(mixed $number, string $length = "long")
 * @method string pressure_pound_per_square_inch(mixed $number, string $length = "long")
 * @method string proportion_karat(mixed $number, string $length = "long")
 * @method string speed_kilometer_per_hour(mixed $number, string $length = "long")
 * @method string speed_meter_per_second(mixed $number, string $length = "long")
 * @method string speed_mile_per_hour(mixed $number, string $length = "long")
 * @method string temperature_celsius(mixed $number, string $length = "long")
 * @method string temperature_fahrenheit(mixed $number, string $length = "long")
 * @method string temperature_kelvin(mixed $number, string $length = "long")
 * @method string volume_acre_foot(mixed $number, string $length = "long")
 * @method string volume_bushel(mixed $number, string $length = "long")
 * @method string volume_centiliter(mixed $number, string $length = "long")
 * @method string volume_cubic_centimeter(mixed $number, string $length = "long")
 * @method string volume_cubic_foot(mixed $number, string $length = "long")
 * @method string volume_cubic_inch(mixed $number, string $length = "long")
 * @method string volume_cubic_kilometer(mixed $number, string $length = "long")
 * @method string volume_cubic_meter(mixed $number, string $length = "long")
 * @method string volume_cubic_mile(mixed $number, string $length = "long")
 * @method string volume_cubic_yard(mixed $number, string $length = "long")
 * @method string volume_cup(mixed $number, string $length = "long")
 * @method string volume_deciliter(mixed $number, string $length = "long")
 * @method string volume_fluid_ounce(mixed $number, string $length = "long")
 * @method string volume_gallon(mixed $number, string $length = "long")
 * @method string volume_hectoliter(mixed $number, string $length = "long")
 * @method string volume_liter(mixed $number, string $length = "long")
 * @method string volume_megaliter(mixed $number, string $length = "long")
 * @method string volume_milliliter(mixed $number, string $length = "long")
 * @method string volume_pint(mixed $number, string $length = "long")
 * @method string volume_quart(mixed $number, string $length = "long")
 * @method string volume_tablespoon(mixed $number, string $length = "long")
 * @method string volume_teaspoon(mixed $number, string $length = "long")
 */
class Units
{
	/**
	 * @uses get_sequence
	 */
	use AccessorTrait;
	use LocalePropertyTrait;

	public const LENGTH_LONG = 'long';
	public const LENGTH_SHORT = 'short';
	public const LENGTH_NARROW = 'narrow';

	public const DEFAULT_LENGTH = self::LENGTH_LONG;
	public const COUNT_PREFIX = 'unitPattern-count-';

	/**
	 * @param string $length One of `LENGTH_*`.
	 *
	 * @return string One of `LocalizedListFormatter::TYPE_UNIT*`.
	 */
	static private function length_to_unit_type(string $length): string
	{
		static $types = [

			self::LENGTH_LONG => LocalizedListFormatter::TYPE_UNIT,
			self::LENGTH_SHORT => LocalizedListFormatter::TYPE_UNIT_SHORT,
			self::LENGTH_NARROW => LocalizedListFormatter::TYPE_UNIT_NARROW,

		];

		return $types[$length];
	}

	/**
	 * @var array
	 */
	private $data;

	/**
	 * @var Plurals
	 */
	private $plurals;

	private function get_sequence(): Sequence
	{
		return new Sequence($this);
	}

	public function __construct(Locale $locale)
	{
		$this->locale = $locale;
		$this->data = $locale['units'];
	}

	public function __call($name, $arguments)
	{
		$unit = strtr($name, '_', '-');

		if (isset($this->data[self::DEFAULT_LENGTH][$unit]))
		{
			$number = array_shift($arguments);

			return $this->format($number, $unit, ...$arguments);
		}

		throw new BadMethodCallException("Unit is not defined: $name.");
	}

	public function __get($property)
	{
		$unit = strtr($property, '_', '-');

		if (isset($this->data[self::DEFAULT_LENGTH][$unit]))
		{
			return $this->$property = new Unit($this, $unit);
		}

		return $this->accessor_get($property);
	}

	/**
	 * @throws LogicException if the specified unit is not defined.
	 */
	public function assert_is_unit(string $unit): void
	{
		if (!isset($this->data[self::DEFAULT_LENGTH][$unit]))
		{
			throw new LogicException("No such unit: $unit");
		}
	}

	public function name_for(string $unit, string $length = self::DEFAULT_LENGTH): string
	{
		$unit = strtr($unit, '_', '-');

		return $this->data[$length][$unit]['displayName'];
	}

	/**
	 * @param int|float $number
	 */
	public function format($number, string $unit, string $length = self::DEFAULT_LENGTH): string
	{
		$pattern = $this->pattern_for_unit($unit, $number, $length);
		$number = $this->ensure_number_if_formatted($number);

		return strtr($pattern, [ '{0}' => $number ]);
	}

	/**
	 * Format a combination of units is X per Y, such as _miles per hour_ or _liters per second_.
	 *
	 * @param int|float $number
	 *
	 * @see http://unicode.org/reports/tr35/tr35-general.html#perUnitPatterns
	 */
	public function format_combination($number, string $number_unit, string $per_unit, string $length = self::DEFAULT_LENGTH): string
	{
		$formatted = $this->format($number, $number_unit, $length);
		$data = $this->data[$length][$per_unit];

		if (isset($data['perUnitPattern']))
		{
			return strtr($data['perUnitPattern'], [

				'{0}' => $formatted

			]);
		}

		$denominator = $this->pattern_for_denominator($per_unit, $number, $length);
		$pattern = $this->pattern_for_combination($length);

		return strtr($pattern, [

			'{0}' => $formatted,
			'{1}' => $denominator

		]);
	}

	/**
	 * Units may be used in composed sequences, such as 5° 30′ for 5 degrees 30 minutes,
	 * or 3 ft 2 in.For that purpose, the appropriate width of the unit listPattern can be used
	 * to compose the units in a sequence.
	 *
	 * @see http://unicode.org/reports/tr35/tr35-general.html#Unit_Sequences
	 */
	public function format_sequence(array $units_and_numbers, string $length = self::DEFAULT_LENGTH): string
	{
		$list = [];

		foreach ($units_and_numbers as $unit => $number)
		{
			$list[] = $this->format($number, $unit, $length);
		}

		return $this->locale->format_list($list, self::length_to_unit_type($length));
	}

	/**
	 * @param int|float $number
	 */
	private function pattern_for_unit(string $unit, $number, string $length): string
	{
		$this->assert_is_unit($unit);

		$count = $this->count_for($number);

		return $this->data[$length][$unit][self::COUNT_PREFIX . $count];
	}

	/**
	 * @param int|float $number
	 */
	private function pattern_for_denominator(string $unit, $number, string $length): string
	{
		$pattern = $this->pattern_for_unit($unit, $number, $length);

		return UTF8Helpers::trim(str_replace('{0}', '', $pattern));
	}

	private function pattern_for_combination(string $length): string
	{
		return $this->data[$length]['per']['compoundUnitPattern'];
	}

	/**
	 * @param int|float $number
	 */
	private function count_for($number): string
	{
		$plurals = &$this->plurals;

		if (!$plurals)
		{
			$plurals = $this->locale->repository->plurals;
		}

		return $plurals->rule_for($number, $this->locale->language);
	}

	/**
	 * @param int|float|string $number
	 */
	private function ensure_number_if_formatted($number): string
	{
		if (is_string($number))
		{
			return $number;
		}

		return $this->locale->format_number($number);
	}
}
