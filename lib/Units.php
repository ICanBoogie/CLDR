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
use ICanBoogie\CLDR\Units\NumberWithUnit;
use ICanBoogie\CLDR\Units\Sequence;
use ICanBoogie\CLDR\Units\Unit;
use LogicException;

use function count;
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
 * @method NumberWithUnit acceleration_g_force(float|int $number)
 * @method NumberWithUnit acceleration_meter_per_second_squared(float|int $number)
 * @method NumberWithUnit angle_arc_minute(float|int $number)
 * @method NumberWithUnit angle_arc_second(float|int $number)
 * @method NumberWithUnit angle_degree(float|int $number)
 * @method NumberWithUnit angle_radian(float|int $number)
 * @method NumberWithUnit area_acre(float|int $number)
 * @method NumberWithUnit area_hectare(float|int $number)
 * @method NumberWithUnit area_square_centimeter(float|int $number)
 * @method NumberWithUnit area_square_foot(float|int $number)
 * @method NumberWithUnit area_square_inch(float|int $number)
 * @method NumberWithUnit area_square_kilometer(float|int $number)
 * @method NumberWithUnit area_square_meter(float|int $number)
 * @method NumberWithUnit area_square_mile(float|int $number)
 * @method NumberWithUnit area_square_yard(float|int $number)
 * @method NumberWithUnit consumption_liter_per_kilometer(float|int $number)
 * @method NumberWithUnit consumption_mile_per_gallon(float|int $number)
 * @method NumberWithUnit digital_bit(float|int $number)
 * @method NumberWithUnit digital_byte(float|int $number)
 * @method NumberWithUnit digital_gigabit(float|int $number)
 * @method NumberWithUnit digital_gigabyte(float|int $number)
 * @method NumberWithUnit digital_kilobit(float|int $number)
 * @method NumberWithUnit digital_kilobyte(float|int $number)
 * @method NumberWithUnit digital_megabit(float|int $number)
 * @method NumberWithUnit digital_megabyte(float|int $number)
 * @method NumberWithUnit digital_terabit(float|int $number)
 * @method NumberWithUnit digital_terabyte(float|int $number)
 * @method NumberWithUnit duration_day(float|int $number)
 * @method NumberWithUnit duration_hour(float|int $number)
 * @method NumberWithUnit duration_microsecond(float|int $number)
 * @method NumberWithUnit duration_millisecond(float|int $number)
 * @method NumberWithUnit duration_minute(float|int $number)
 * @method NumberWithUnit duration_month(float|int $number)
 * @method NumberWithUnit duration_nanosecond(float|int $number)
 * @method NumberWithUnit duration_second(float|int $number)
 * @method NumberWithUnit duration_week(float|int $number)
 * @method NumberWithUnit duration_year(float|int $number)
 * @method NumberWithUnit electric_ampere(float|int $number)
 * @method NumberWithUnit electric_milliampere(float|int $number)
 * @method NumberWithUnit electric_ohm(float|int $number)
 * @method NumberWithUnit electric_volt(float|int $number)
 * @method NumberWithUnit energy_calorie(float|int $number)
 * @method NumberWithUnit energy_foodcalorie(float|int $number)
 * @method NumberWithUnit energy_joule(float|int $number)
 * @method NumberWithUnit energy_kilocalorie(float|int $number)
 * @method NumberWithUnit energy_kilojoule(float|int $number)
 * @method NumberWithUnit energy_kilowatt_hour(float|int $number)
 * @method NumberWithUnit frequency_gigahertz(float|int $number)
 * @method NumberWithUnit frequency_hertz(float|int $number)
 * @method NumberWithUnit frequency_kilohertz(float|int $number)
 * @method NumberWithUnit frequency_megahertz(float|int $number)
 * @method NumberWithUnit length_astronomical_unit(float|int $number)
 * @method NumberWithUnit length_centimeter(float|int $number)
 * @method NumberWithUnit length_decimeter(float|int $number)
 * @method NumberWithUnit length_fathom(float|int $number)
 * @method NumberWithUnit length_foot(float|int $number)
 * @method NumberWithUnit length_furlong(float|int $number)
 * @method NumberWithUnit length_inch(float|int $number)
 * @method NumberWithUnit length_kilometer(float|int $number)
 * @method NumberWithUnit length_light_year(float|int $number)
 * @method NumberWithUnit length_meter(float|int $number)
 * @method NumberWithUnit length_micrometer(float|int $number)
 * @method NumberWithUnit length_mile(float|int $number)
 * @method NumberWithUnit length_millimeter(float|int $number)
 * @method NumberWithUnit length_nanometer(float|int $number)
 * @method NumberWithUnit length_nautical_mile(float|int $number)
 * @method NumberWithUnit length_parsec(float|int $number)
 * @method NumberWithUnit length_picometer(float|int $number)
 * @method NumberWithUnit length_yard(float|int $number)
 * @method NumberWithUnit light_lux(float|int $number)
 * @method NumberWithUnit mass_carat(float|int $number)
 * @method NumberWithUnit mass_gram(float|int $number)
 * @method NumberWithUnit mass_kilogram(float|int $number)
 * @method NumberWithUnit mass_metric_ton(float|int $number)
 * @method NumberWithUnit mass_microgram(float|int $number)
 * @method NumberWithUnit mass_milligram(float|int $number)
 * @method NumberWithUnit mass_ounce(float|int $number)
 * @method NumberWithUnit mass_ounce_troy(float|int $number)
 * @method NumberWithUnit mass_pound(float|int $number)
 * @method NumberWithUnit mass_stone(float|int $number)
 * @method NumberWithUnit mass_ton(float|int $number)
 * @method NumberWithUnit power_gigawatt(float|int $number)
 * @method NumberWithUnit power_horsepower(float|int $number)
 * @method NumberWithUnit power_kilowatt(float|int $number)
 * @method NumberWithUnit power_megawatt(float|int $number)
 * @method NumberWithUnit power_milliwatt(float|int $number)
 * @method NumberWithUnit power_watt(float|int $number)
 * @method NumberWithUnit pressure_hectopascal(float|int $number)
 * @method NumberWithUnit pressure_inch_hg(float|int $number)
 * @method NumberWithUnit pressure_millibar(float|int $number)
 * @method NumberWithUnit pressure_millimeter_of_mercury(float|int $number)
 * @method NumberWithUnit pressure_pound_per_square_inch(float|int $number)
 * @method NumberWithUnit proportion_karat(float|int $number)
 * @method NumberWithUnit speed_kilometer_per_hour(float|int $number)
 * @method NumberWithUnit speed_meter_per_second(float|int $number)
 * @method NumberWithUnit speed_mile_per_hour(float|int $number)
 * @method NumberWithUnit temperature_celsius(float|int $number)
 * @method NumberWithUnit temperature_fahrenheit(float|int $number)
 * @method NumberWithUnit temperature_kelvin(float|int $number)
 * @method NumberWithUnit volume_acre_foot(float|int $number)
 * @method NumberWithUnit volume_bushel(float|int $number)
 * @method NumberWithUnit volume_centiliter(float|int $number)
 * @method NumberWithUnit volume_cubic_centimeter(float|int $number)
 * @method NumberWithUnit volume_cubic_foot(float|int $number)
 * @method NumberWithUnit volume_cubic_inch(float|int $number)
 * @method NumberWithUnit volume_cubic_kilometer(float|int $number)
 * @method NumberWithUnit volume_cubic_meter(float|int $number)
 * @method NumberWithUnit volume_cubic_mile(float|int $number)
 * @method NumberWithUnit volume_cubic_yard(float|int $number)
 * @method NumberWithUnit volume_cup(float|int $number)
 * @method NumberWithUnit volume_deciliter(float|int $number)
 * @method NumberWithUnit volume_fluid_ounce(float|int $number)
 * @method NumberWithUnit volume_gallon(float|int $number)
 * @method NumberWithUnit volume_hectoliter(float|int $number)
 * @method NumberWithUnit volume_liter(float|int $number)
 * @method NumberWithUnit volume_megaliter(float|int $number)
 * @method NumberWithUnit volume_milliliter(float|int $number)
 * @method NumberWithUnit volume_pint(float|int $number)
 * @method NumberWithUnit volume_quart(float|int $number)
 * @method NumberWithUnit volume_tablespoon(float|int $number)
 * @method NumberWithUnit volume_teaspoon(float|int $number)
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
	 * @return LocalizedListFormatter::TYPE_UNIT_*
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
	 * @var array<string, mixed>
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

	public function __call(string $name, array $arguments): NumberWithUnit
	{
		$unit = strtr($name, '_', '-');

		if (empty($this->data[self::DEFAULT_LENGTH][$unit]))
		{
			throw new BadMethodCallException("No such unit: $unit");
		}

		$n = count($arguments);

		if ($n !== 1)
		{
			throw new BadMethodCallException("$name() expects one argument, got $n");
		}

		$number = $arguments[0];

		return new NumberWithUnit($number + 0, $unit, $this);
	}

	/**
	 * @return mixed
	 */
	public function __get(string $property)
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
		if (empty($this->data[self::DEFAULT_LENGTH][$unit]))
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
	public function format_combination(
		$number,
		string $number_unit,
		string $per_unit,
		string $length = self::DEFAULT_LENGTH
	): string {
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
	 * or 3 ft 2 in. For that purpose, the appropriate width of the unit listPattern can be used
	 * to compose the units in a sequence.
	 *
	 * @param array<string, int|float> $units_and_numbers
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

		if (!$plurals) // @phpstan-ignore-line
		{
			$plurals = $this->locale->repository->plurals;
		}

		return $plurals->rule_for($number, $this->locale->language);
	}

	/**
	 * @param numeric $number
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
