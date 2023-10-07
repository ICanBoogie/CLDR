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

use ICanBoogie\CLDR\Units\Sequence;
use ICanBoogie\CLDR\Units\Unit;
use ICanBoogie\CLDR\Units\UnitsCompanion;
use ICanBoogie\PropertyNotDefined;
use LogicException;

use function is_string;
use function str_replace;
use function strtr;

class Units
{
    use UnitsCompanion;

	public const DEFAULT_LENGTH = UnitLength::LONG;
	public const COUNT_PREFIX = 'unitPattern-count-';

	static private function length_to_unit_type(UnitLength $length): ListType
	{
		return match ($length) {
			UnitLength::LONG => ListType::UNIT,
			UnitLength::SHORT => ListType::UNIT_SHORT,
			UnitLength::NARROW => ListType::UNIT_NARROW,
		};
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private readonly array $data;

	public readonly Sequence $sequence;

	public function __construct(
		public readonly Locale $locale
	) {
		/** @phpstan-ignore-next-line */
		$this->data = $locale['units'];
		$this->sequence = new Sequence($this);
	}

	/**
	 * @var array<string, Unit>
	 *     Where _key_ is a unit name.
	 */
	private array $units = [];

	/**
	 * @return mixed
	 */
	public function __get(string $property)
	{
		$unit = strtr($property, '_', '-');

		if (isset($this->data[self::DEFAULT_LENGTH->value][$unit])) {
			return $this->units[$property] ??= new Unit($this, $unit);
		}

		throw new PropertyNotDefined(property: $property, container: $this);
	}

	/**
	 * @throws LogicException if the specified unit is not defined.
	 */
	public function assert_is_unit(string $unit): void
	{
		$this->data[self::DEFAULT_LENGTH->value][$unit]
			?? throw new LogicException("No such unit: $unit");
	}

	public function name_for(string $unit, UnitLength $length = self::DEFAULT_LENGTH): string
	{
		$unit = strtr($unit, '_', '-');

		return $this->data[$length->value][$unit]['displayName'];
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	public function format(float|int|string $number, string $unit, UnitLength $length = self::DEFAULT_LENGTH): string
	{
		$pattern = $this->pattern_for_unit($unit, $number, $length);
		$number = $this->ensure_number_if_formatted($number);

		return strtr($pattern, ['{0}' => $number]);
	}

	/**
	 * Format a combination of units is X per Y, such as _miles per hour_ or _liters per second_.
	 *
	 * @param float|int|numeric-string $number
	 *
	 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#compound-units
	 */
	public function format_compound(
		float|int|string $number,
		string $number_unit,
		string $per_unit,
		UnitLength $length = self::DEFAULT_LENGTH
	): string {
		$formatted = $this->format($number, $number_unit, $length);
		$data = $this->data[$length->value][$per_unit];

		if (isset($data['perUnitPattern'])) {
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
	 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#Unit_Sequences
	 */
	public function format_sequence(array $units_and_numbers, UnitLength $length = self::DEFAULT_LENGTH): string
	{
		$list = [];

		foreach ($units_and_numbers as $unit => $number) {
			$list[] = $this->format($number, $unit, $length);
		}

		return $this->locale->format_list($list, self::length_to_unit_type($length));
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	private function pattern_for_unit(string $unit, float|int|string $number, UnitLength $length): string
	{
		$this->assert_is_unit($unit);

		$count = $this->count_for($number);

		return $this->data[$length->value][$unit][self::COUNT_PREFIX . $count];
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	private function pattern_for_denominator(string $unit, float|int|string $number, UnitLength $length): string
	{
		$pattern = $this->pattern_for_unit($unit, $number, $length);

		return UTF8Helpers::trim(str_replace('{0}', '', $pattern));
	}

	private function pattern_for_combination(UnitLength $length): string
	{
		return $this->data[$length->value]['per']['compoundUnitPattern'];
	}

	private Plurals $plurals;

	/**
	 * @param float|int|numeric-string $number
	 */
	private function count_for(float|int|string $number): string
	{
		$plurals = $this->plurals ??= $this->locale->repository->plurals;

		return $plurals->rule_for($number, $this->locale->language);
	}

	/**
	 * @param float|int|numeric-string $number
	 */
	private function ensure_number_if_formatted(float|int|string $number): string
	{
		if (is_string($number)) {
			return $number;
		}

		return $this->locale->format_number($number);
	}
}
