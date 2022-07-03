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
 * @property-read array $decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-{defaultNumberingSystem}`.
 * @property-read string $decimal_format
 *     Shortcuts to the `decimalFormats-numberSystem-{defaultNumberingSystem}/standard`.
 * @property-read array $short_decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-{defaultNumberingSystem}/short/decimalFormats`.
 * @property-read array $long_decimal_formats
 *     Shortcuts to the `decimalFormats-numberSystem-{defaultNumberingSystem}/long/decimalFormats`.
 * @property-read array $scientific_formats
 *     Shortcuts to the `scientificFormats-numberSystem-{defaultNumberingSystem}`.
 * @property-read array $percent_formats
 *     Shortcuts to the `percentFormats-numberSystem-{defaultNumberingSystem}`.
 * @property-read array $currency_formats
 *     Shortcuts to the `currencyFormats-numberSystem-{defaultNumberingSystem}`.
 * @property-read array $misc_patterns
 *     Shortcuts to the `miscPatterns-numberSystem-{defaultNumberingSystem}`.
 *
 * @extends ArrayObject<string, mixed>
 */
final class Numbers extends ArrayObject
{
	/**
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
	use LocalePropertyTrait;

	private function lazy_get_symbols(): Symbols
	{
		return Symbols::from($this['symbols-numberSystem-' . $this['defaultNumberingSystem']]);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_decimal_formats(): array
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	private function get_decimal_format(): string
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['standard'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_short_decimal_formats(): array
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['short']['decimalFormat'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_long_decimal_formats(): array
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['long']['decimalFormat'];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_scientific_formats(): array
	{
		return $this['scientificFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_percent_formats(): array
	{
		return $this['percentFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_currency_formats(): array
	{
		return $this['currencyFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private function get_misc_patterns(): array
	{
		return $this['miscPatterns-numberSystem-' . $this['defaultNumberingSystem']];
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public function __construct(Locale $locale, array $data)
	{
		$this->locale = $locale;

		parent::__construct($data);
	}
}
