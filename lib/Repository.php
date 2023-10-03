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

use function array_shift;
use function explode;

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
 * @property-read LocaleCollection $locales
 * @uses self::lazy_get_locales()
 * @property-read Supplemental $supplemental
 * @uses self::lazy_get_supplemental()
 * @property-read TerritoryCollection $territories
 * @uses self::lazy_get_territories()
 * @property-read CurrencyCollection $currencies
 * @uses self::lazy_get_currencies()
 * @property-read NumberFormatter $number_formatter
 * @uses self::lazy_get_number_formatter()
 * @property-read CurrencyFormatter $currency_formatter
 * @uses self::lazy_get_currency_formatter()
 * @property-read ListFormatter $list_formatter
 * @uses self::lazy_get_list_formatter()
 * @property-read Plurals $plurals
 * @uses self::lazy_get_plurals()
 * @property-read string[] $available_locales
 * @uses self::lazy_get_available_locales()
 */
final class Repository
{
	/**
	 * @uses get_locales
	 * @uses get_supplemental
	 * @uses get_territories
	 * @uses get_currencies
	 * @uses get_number_formatter
	 * @uses get_currency_formatter
	 * @uses get_list_formatter
	 * @uses get_list_formatter
	 * @uses get_plurals
	 * @uses get_available_locales
	 */
	use AccessorTrait;

	private LocaleCollection $locales;

	private function get_locales(): LocaleCollection
	{
		return $this->locales ??= new LocaleCollection($this);
	}

	private Supplemental $supplemental;

	private function get_supplemental(): Supplemental
	{
		return $this->supplemental ??= new Supplemental($this);
	}

	private TerritoryCollection $territories;

	private function get_territories(): TerritoryCollection
	{
		return $this->territories ??= new TerritoryCollection($this);
	}

	private CurrencyCollection $currencies;

	private function get_currencies(): CurrencyCollection
	{
		return $this->currencies ??= new CurrencyCollection($this);
	}

	private NumberFormatter $number_formatter;

	private function get_number_formatter(): NumberFormatter
	{
		return $this->number_formatter ??= new NumberFormatter();
	}

	private CurrencyFormatter $currency_formatter;

	private function get_currency_formatter(): CurrencyFormatter
	{
		return $this->currency_formatter ??= new CurrencyFormatter();
	}

	private ListFormatter $list_formatter;

	private function get_list_formatter(): ListFormatter
	{
		return $this->list_formatter ??= new ListFormatter();
	}

	private Plurals $plurals;

	private function get_plurals(): Plurals
	{
		/** @phpstan-ignore-next-line */
		return $this->plurals ??= new Plurals($this->supplemental['plurals']);
	}

	/**
	 * @var array<string>
	 */
	private array $available_locales;

	/**
	 * @return array<string>
	 *
	 * @throws ResourceNotFound
	 */
	private function get_available_locales(): array
	{
		return $this->available_locales ??= $this->fetch('core/availableLocales', 'availableLocales/modern');
	}

	public function __construct(
		public readonly Provider $provider
	) {
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * @param string|null $data_path Path to the data to extract.
	 *
	 * @throws ResourceNotFound
	 *
	 * @phpstan-ignore-next-line
	 */
	public function fetch(string $path, string $data_path = null): array
	{
		$data = $this->provider->provide($path);

		if ($data_path) {
			$data_path = explode('/', $data_path);

			while ($data_path)
			{
				$p = array_shift($data_path);
				$data = $data[$p];
			}
		}

		return $data;
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * Note, if the pattern contains '%', the number will be multiplied by 100 first. If the
	 * pattern contains '‰', the number will be multiplied by 1000.
	 *
	 * @param float|int|numeric-string $number
	 *     The number to format.
	 * @param string|NumberPattern $pattern
	 *     The pattern used to format the number.
	 */
	public function format_number(
		float|int|string $number,
		NumberPattern|string $pattern,
		Symbols $symbols = null,
	): string {
		return $this->number_formatter->format($number, $pattern, $symbols);
	}

	/**
	 * Format a number with the specified pattern.
	 *
	 * @param float|int|numeric-string $number
	 *      The number to format.
	 *
	 * @see CurrencyFormatter::format()
	 */
	public function format_currency(
		float|int|string $number,
		NumberPattern|string $pattern,
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
		return in_array($locale, $this->get_available_locales());
	}
}
