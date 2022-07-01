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
use ICanBoogie\CLDR\Locale\ListPattern;
use ICanBoogie\CLDR\Numbers\Symbols;

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
final class Repository
{
	/**
	 * @uses get_provider
	 * @uses lazy_get_locales
	 * @uses lazy_get_supplemental
	 * @uses lazy_get_territories
	 * @uses lazy_get_currencies
	 * @uses lazy_get_number_formatter
	 * @uses lazy_get_currency_formatter
	 * @uses lazy_get_list_formatter
	 * @uses lazy_get_list_formatter
	 * @uses lazy_get_plurals
	 * @uses lazy_get_available_locales
	 */
	use AccessorTrait;

	/**
	 * @var Provider
	 */
	private $provider;

	private function get_provider(): Provider
	{
		return $this->provider;
	}

	private function lazy_get_locales(): LocaleCollection
	{
		return new LocaleCollection($this);
	}

	private function lazy_get_supplemental(): Supplemental
	{
		return new Supplemental($this);
	}

	private function lazy_get_territories(): TerritoryCollection
	{
		return new TerritoryCollection($this);
	}

	private function lazy_get_currencies(): CurrencyCollection
	{
		return new CurrencyCollection($this);
	}

	private function lazy_get_number_formatter(): NumberFormatter
	{
		return new NumberFormatter();
	}

	private function lazy_get_currency_formatter(): CurrencyFormatter
	{
		return new CurrencyFormatter();
	}

	private function lazy_get_list_formatter(): ListFormatter
	{
		return new ListFormatter();
	}

	private function lazy_get_plurals(): Plurals
	{
		return new Plurals($this->supplemental['plurals']);
	}

	/**
	 * @return string[]
	 *
	 * @throws ResourceNotFound
	 */
	private function lazy_get_available_locales(): array
	{
		return $this->fetch('availableLocales')['availableLocales']['modern'];
	}

	public function __construct(Provider $provider)
	{
		$this->provider = $provider;
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * @return array<string, mixed>
	 *
	 * @throws ResourceNotFound
	 */
	public function fetch(string $path): array
	{
		return $this->provider->provide($path);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * @param float|int $number The number to be formatted.
	 *
	 * @see NumberFormatter::format()
	 */
	public function format_number($number, string $pattern, Symbols $symbols = null): string
	{
		return $this->number_formatter->format($number, $pattern, $symbols);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * @param float|int $number The number to be formatted.
	 *
	 * @see CurrencyFormatter::format()
	 */
	public function format_currency(
		$number,
		string $pattern,
		Symbols $symbols = null,
		string $currencySymbol = CurrencyFormatter::DEFAULT_CURRENCY_SYMBOL
	): string {
		return $this->currency_formatter->format($number, $pattern, $symbols, $currencySymbol);
	}

	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 *
	 * @see ListFormatter::format()
	 */
	public function format_list(array $list, ListPattern $list_pattern): string
	{
		return $this->list_formatter->format($list, $list_pattern);
	}

	/**
	 * Whether a locale is available.
	 */
	public function is_locale_available(string $locale): bool
	{
		return in_array($locale, $this->available_locales);
	}
}
