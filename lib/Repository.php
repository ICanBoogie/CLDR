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

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Representation of a CLDR.
 *
 * <pre>
 * <?php
 *
 * namespace ICanBoogie\CLDR;
 *
 * $repository = new Repository($provider);
 *
 * var_dump($repository->locales['fr']);
 * var_dump($repository->territories['FR']);
 * </pre>
 *
 * @property-read Provider $provider
 * @property-read LocaleCollection $locales
 * @property-read Supplemental $supplemental
 * @property-read TerritoryCollection $territories
 * @property-read CurrencyCollection $currencies
 * @property-read NumberFormatter $number_formatter
 * @property-read CurrencyFormatter $currency_formatter
 * @property-read ListFormatter $list_formatter
 * @property-read Plurals $plurals
 * @property-read array $available_locales
 *
 * @see http://www.unicode.org/repos/cldr-aux/json/24/
 */
class Repository
{
	use AccessorTrait;

	/**
	 * @var Provider
	 */
	private $provider;

	/**
	 * @return Provider
	 */
	protected function get_provider()
	{
		return $this->provider;
	}

	/**
	 * @return LocaleCollection
	 */
	protected function lazy_get_locales()
	{
		return new LocaleCollection($this);
	}

	/**
	 * @return Supplemental
	 */
	protected function lazy_get_supplemental()
	{
		return new Supplemental($this);
	}

	/**
	 * @return TerritoryCollection
	 */
	protected function lazy_get_territories()
	{
		return new TerritoryCollection($this);
	}

	/**
	 * @return CurrencyCollection
	 */
	protected function lazy_get_currencies()
	{
		return new CurrencyCollection($this);
	}

	/**
	 * @return NumberFormatter
	 */
	protected function lazy_get_number_formatter()
	{
		return new NumberFormatter($this);
	}

	/**
	 * @return CurrencyFormatter
	 */
	protected function lazy_get_currency_formatter()
	{
		return new CurrencyFormatter($this);
	}

	/**
	 * @return ListFormatter
	 */
	protected function lazy_get_list_formatter()
	{
		return new ListFormatter($this);
	}

	/**
	 * @return Plurals
	 */
	protected function lazy_get_plurals()
	{
		return new Plurals($this->supplemental['plurals']);
	}

	/**
	 * @return array
	 */
	protected function lazy_get_available_locales()
	{
		return $this->fetch('availableLocales')['availableLocales']['modern'];
	}

	/**
	 * Initializes the {@link $provider} property.
	 *
	 * @param Provider $provider
	 */
	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * Note: The method is forwarded to {@link Provider::provide}.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function fetch($path)
	{
		return $this->provider->provide($path);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * @param mixed $number The number to be formatted.
	 * @param string $pattern The pattern used to format the number.
	 * @param array $symbols Symbols.
	 *
	 * @return string
	 *
	 * @see NumberFormatter::format()
	 */
	public function format_number($number, $pattern, array $symbols = [])
	{
		return $this->number_formatter->format($number, $pattern, $symbols);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * @param mixed $number The number to be formatted.
	 * @param string $pattern The pattern used to format the number.
	 * @param array $symbols Symbols.
	 *
	 * @return string
	 *
	 * @see CurrencyFormatter::format()
	 */
	public function format_currency($number, $pattern, array $symbols = [])
	{
		return $this->currency_formatter->format($number, $pattern, $symbols);
	}

	/**
	 * Formats a variable-length lists of things.
	 *
	 * @param array $list The list to format.
	 * @param array $list_patterns A list patterns.
	 *
	 * @return string
	 *
	 * @see ListFormatter::format()
	 */
	public function format_list(array $list, array $list_patterns)
	{
		return $this->list_formatter->format($list, $list_patterns);
	}

	/**
	 * @param string $locale
	 *
	 * @return bool `true` if the locale is available, `false` otherwise.
	 */
	public function is_locale_available($locale)
	{
		return in_array($locale, $this->available_locales);
	}
}
