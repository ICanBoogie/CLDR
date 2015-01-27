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

/**
 * Representation of a locale numbers
 *
 * @property-read Locale $locale
 * @property-read array $symbols Shortcuts to the `symbols-numberSystem-<defaultNumberingSystem>`.
 * @property-read array $decimal_formats Shortcuts to the `decimalFormats-numberSystem-<defaultNumberingSystem>`.
 * @property-read string $decimal_format Shortcuts to the `decimalFormats-numberSystem-<defaultNumberingSystem>/standard`.
 * @property-read array $short_decimal_formats Shortcuts to the `decimalFormats-numberSystem-<defaultNumberingSystem>/short/decimalFormats`.
 * @property-read array $long_decimal_formats Shortcuts to the `decimalFormats-numberSystem-<defaultNumberingSystem>/long/decimalFormats`.
 * @property-read array $scientific_formats Shortcuts to the `scientificFormats-numberSystem-<defaultNumberingSystem>`.
 * @property-read array $percent_formats Shortcuts to the `percentFormats-numberSystem-<defaultNumberingSystem>`.
 * @property-read array $currency_formats Shortcuts to the `currencyFormats-numberSystem-<defaultNumberingSystem>`.
 * @property-read array $misc_patterns Shortcuts to the `miscPatterns-numberSystem-<defaultNumberingSystem>`.
 */
class Numbers extends \ArrayObject
{
	use AccessorTrait;
	use LocalePropertyTrait;

	protected function get_symbols()
	{
		return $this['symbols-numberSystem-' . $this['defaultNumberingSystem']];
	}

	protected function get_decimal_formats()
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	protected function get_decimal_format()
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['standard'];
	}

	protected function get_short_decimal_formats()
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['short']['decimalFormat'];
	}

	protected function get_long_decimal_formats()
	{
		return $this['decimalFormats-numberSystem-' . $this['defaultNumberingSystem']]['long']['decimalFormat'];
	}

	protected function get_scientific_formats()
	{
		return $this['scientificFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	protected function get_percent_formats()
	{
		return $this['percentFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	protected function get_currency_formats()
	{
		return $this['currencyFormats-numberSystem-' . $this['defaultNumberingSystem']];
	}

	protected function get_misc_patterns()
	{
		return $this['miscPatterns-numberSystem-' . $this['defaultNumberingSystem']];
	}

	/**
	 * Initialize the {@link $locale} property.
	 *
	 * @param Locale $locale
	 * @param array $data
	 */
	public function __construct(Locale $locale, array $data)
	{
		$this->locale = $locale;

		parent::__construct($data);
	}
}
