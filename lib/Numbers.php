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

use ArrayObject;
use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Numbers\Symbols;

/**
 * Numbers for a locale.
 *
 * @property-read Locale $locale
 * @property-read Symbols $symbols
 * @property-read string $default_numbering_system
 *     Indicates which numbering system should be used for presentation of numeric quantities in the given locale.
 * @property-read array $decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-$default_numbering_system`.
 * @property-read string $decimal_format
 *     Shortcuts to the `decimalFormats-numberSystem-$default_numbering_system/standard`.
 * @property-read array $short_decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-$default_numbering_system/short/decimalFormats`.
 * @property-read array $long_decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-$default_numbering_system/long/decimalFormats`.
 * @property-read array $scientific_formats
 *     Shortcuts to the `scientificFormats-numberSystem-$default_numbering_system`.
 * @property-read array $percent_formats
 *     Shortcuts to the `percentFormats-numberSystem-$default_numbering_system`.
 * @property-read array $currency_formats
 *     Shortcuts to the `currencyFormats-numberSystem-$default_numbering_system`.
 * @property-read array $misc_patterns
 *     Shortcuts to the `miscPatterns-numberSystem-$default_numbering_system`.
 *
 * @extends ArrayObject<string, mixed>
 *
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#1-numbering-systems
 */
final class Numbers extends ArrayObject
{
	/**
	 * @uses lazy_get_default_numbering_system
	 * @uses lazy_get_symbols
	 * @uses get_decimal_formats
	 * @uses get_decimal_format
	 * @uses get_short_decimal_formats
	 * @uses get_long_decimal_formats
	 * @uses get_scientific_formats
	 * @uses get_percent_formats
	 * @uses get_currency_formats
	 * @uses get_misc_patterns
	 */
	use AccessorTrait;

	private function lazy_get_default_numbering_system(): string
	{
		return $this['defaultNumberingSystem'];
	}

	private function lazy_get_symbols(): Symbols
	{
		return Symbols::from($this["symbols-numberSystem-$this->default_numbering_system"]);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_decimal_formats(): array
	{
		return $this["decimalFormats-numberSystem-$this->default_numbering_system"];
	}

	private function get_decimal_format(): string
	{
		return $this["decimalFormats-numberSystem-$this->default_numbering_system"]['standard'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_short_decimal_formats(): array
	{
		return $this["decimalFormats-numberSystem-$this->default_numbering_system"]['short']['decimalFormat'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_long_decimal_formats(): array
	{
		return $this["decimalFormats-numberSystem-$this->default_numbering_system"]['long']['decimalFormat'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_scientific_formats(): array
	{
		return $this["scientificFormats-numberSystem-$this->default_numbering_system"];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_percent_formats(): array
	{
		return $this["percentFormats-numberSystem-$this->default_numbering_system"];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_currency_formats(): array
	{
		return $this["currencyFormats-numberSystem-$this->default_numbering_system"];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_misc_patterns(): array
	{
		return $this["miscPatterns-numberSystem-$this->default_numbering_system"];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public function __construct(
		public readonly Locale $locale,
		array $data
	) {
		parent::__construct($data);
	}
}
