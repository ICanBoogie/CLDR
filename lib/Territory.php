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

use DateTimeImmutable;
use DateTimeInterface;
use ICanBoogie\Accessor\AccessorTrait;
use InvalidArgumentException;
use Throwable;
use function current;
use function extract;
use function in_array;
use function key;
use function strlen;
use function strpos;
use function substr;

/**
 * A territory.
 *
 * @property-read array $containment The `territoryContainment` data.
 * @property-read array $info The `territoryInfo` data.
 * @property-read array $currencies The currencies available in the country.
 * @property-read Currency|null $currency The current currency.
 * @property-read string $first_day The code of the first day of the week for the territory.
 * @property-read string $weekend_start The code of the first day of the weekend.
 * @property-read string $weekend_end The code of the last day of the weekend.
 * @property-read string|bool $language The ISO code of the official language, or `false' if it
 * cannot be determined.
 * @property-read string $name_as_* The name of the territory in the specified language.
 * @property-read int $population The population of the territory.
 *
 * @see http://www.unicode.org/reports/tr35/tr35-numbers.html#Supplemental_Currency_Data
 */
final class Territory
{
	/**
	 * @uses lazy_get_containment
	 * @uses lazy_get_currencies
	 * @uses lazy_get_currency
	 * @uses lazy_get_info
	 * @uses lazy_get_language
	 * @uses get_first_day
	 * @uses get_weekend_start
	 * @uses get_weekend_end
	 * @uses get_population
	 */
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	private function lazy_get_containment(): array
	{
		return $this->retrieve_from_supplemental('territoryContainment');
	}

	private function lazy_get_currencies(): array
	{
		return $this->repository->supplemental['currencyData']['region'][$this->code];
	}

	/**
	 * @throws Throwable
	 */
	private function lazy_get_currency(): ?Currency
	{
		return $this->currency_at();
	}

	private function lazy_get_info(): array
	{
		return $this->retrieve_from_supplemental('territoryInfo');
	}

	/**
	 * Return the ISO code of the official language of the territory.
	 *
	 * @return string|bool The ISO code of the official language, or `false' if it cannot be
	 * determined.
	 */
	private function lazy_get_language()
	{
		$info = $this->info;

		foreach ($info['languagePopulation'] as $language => $lp)
		{
			if (empty($lp['_officialStatus']) || ($lp['_officialStatus'] != "official" && $lp['_officialStatus'] != "de_facto_official"))
			{
				continue;
			}

			return $language;
		}

		return false;
	}

	private function get_first_day(): string
	{
		return $this->resolve_week_data('firstDay');
	}

	private function get_weekend_start(): string
	{
		return $this->resolve_week_data('weekendStart');
	}

	private function get_weekend_end(): string
	{
		return $this->resolve_week_data('weekendEnd');
	}

	private function get_population(): int
	{
		$info = $this->info;

		return (int) $info['_population'];
	}

	public function __construct(Repository $repository, string $code)
	{
		$this->repository = $repository;
		$this->code = $code;

		$repository->territories->assert_defined($code);
	}

	public function __get($property)
	{
		if (strpos($property, 'name_as_') === 0)
		{
			$locale_code = substr($property, strlen('name_as_'));
			$locale_code = strtr($locale_code, '_', '-');

			return $this->name_as($locale_code);
		}

		return $this->accessor_get($property);
	}

	private function retrieve_from_supplemental(string $section): array
	{
		return $this->repository->supplemental[$section][$this->code];
	}

	/**
	 * Return the currency used in the territory at a point in time.
	 *
	 * @param DateTimeInterface|mixed $date
	 *
	 * @throws Throwable
	 */
	public function currency_at($date = null): ?Currency
	{
		$date = $this->ensure_is_datetime($date);
		$code = $this->find_currency_at($this->currencies, $date->format('Y-m-d'));

		if (!$code)
		{
			return null;
		}

		return new Currency($this->repository, $code);
	}

	/**
	 * Return the currency in a list used at a point in time.
	 */
	private function find_currency_at(array $currencies, string $normalized_date): string
	{
		$rc = false;

		foreach ($currencies as $currency)
		{
			$name = key($currency);
			$interval = current($currency);
			$_from = null;
			$_to = null;
			extract($interval);

			if (($_from && $_from > $normalized_date) || ($_to && $_to < $normalized_date))
			{
				continue;
			}

			$rc = $name;
		}

		return $rc;
	}

	/**
	 * Whether the territory contains the specified territory.
	 */
	public function is_containing(string $code): bool
	{
		$containment = $this->containment;

		return in_array($code, $containment['_contains']);
	}

	/**
	 * Return the name of the territory localized according to the specified locale code.
	 */
	public function name_as(string $locale_code): string
	{
		return $this->localize($locale_code)->name;
	}

	/**
	 * Localize the currency.
	 */
	public function localize(string $locale_code): LocalizedTerritory
	{
		return $this->repository->locales[$locale_code]->localize($this);
	}

	private function resolve_week_data(string $which): string
	{
		$code = $this->code;
		$data = $this->repository->supplemental['weekData'][$which];

		return empty($data[$code]) ? $data['001'] : $data[$code];
	}

	/**
	 * @param DateTimeInterface|string $datetime
	 *
	 * @throws Throwable
	 */
	private function ensure_is_datetime($datetime): DateTimeInterface
	{
		if ($datetime === null)
		{
			return new DateTimeImmutable();
		}

		return $datetime instanceof DateTimeInterface
			? $datetime
			: new DateTimeImmutable($datetime);
	}
}
