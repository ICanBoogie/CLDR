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
use ICanBoogie\CLDR\Numbers\Symbols;

/**
 * Numbers for a locale.
 *
 * @extends ArrayObject<string, mixed>
 *
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#1-numbering-systems
 */
final class Numbers extends ArrayObject
{
	public readonly Symbols $symbols;

	/**
	 * Indicates which numbering system should be used for presentation of numeric quantities in the given locale.
	 */
	public readonly string $default_numbering_system;

	/**
	 * @phpstan-ignore-next-line
	 */
	public readonly  array $decimal_formats;

	/**
	 * Shortcut to the `decimalFormats-numberSystem-$default_numbering_system/standard`.
	 */
	public readonly string $decimal_format;

	/**
	 * Shortcut to the `decimalFormats-numberSystem-$default_numbering_system/short/decimalFormats`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $short_decimal_formats;

	/**
	 * Shortcut to the `decimalFormats-numberSystem-$default_numbering_system/long/decimalFormats`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $long_decimal_formats;

	/**
	 * Shortcut to the `scientificFormats-numberSystem-$default_numbering_system`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $scientific_formats;

	/**
	 * Shortcut to the `percentFormats-numberSystem-$default_numbering_system`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $percent_formats;

	/**
	 * Shortcut to the `currencyFormats-numberSystem-$default_numbering_system`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $currency_formats;

	/**
	 * Shortcut to the `miscPatterns-numberSystem-$default_numbering_system`.
	 *
	 * @phpstan-ignore-next-line
	 */
	public readonly array $misc_patterns;

	/**
	 * @param array<string, mixed> $data
	 */
	public function __construct(
		public readonly Locale $locale,
		array $data
	) {
		parent::__construct($data);

		$this->default_numbering_system = $dns = $data['defaultNumberingSystem'];
		$this->decimal_formats = $data["decimalFormats-numberSystem-$dns"];
		$this->decimal_format = $this->decimal_formats['standard'];
		$this->short_decimal_formats = $this->decimal_formats['short']['decimalFormat'];
		$this->long_decimal_formats = $this->decimal_formats['long']['decimalFormat'];
		$this->scientific_formats = $data["scientificFormats-numberSystem-$dns"];
		$this->percent_formats = $data["percentFormats-numberSystem-$dns"];
		$this->currency_formats = $data["currencyFormats-numberSystem-$dns"];
		$this->misc_patterns = $data["miscPatterns-numberSystem-$dns"];
		$this->symbols = Symbols::from($data["symbols-numberSystem-$dns"]);
	}
}
