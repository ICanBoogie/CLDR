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
use Throwable;

use function current;
use function extract;
use function in_array;
use function key;
use function strlen;
use function substr;

/**
 * A territory.
 *
 * @property-read array<string, mixed> $containment The `territoryContainment` data.
 * @property-read array<string, mixed> $info The `territoryInfo` data.
 * @property-read array<string, mixed> $currencies The currencies available in the country.
 * @property-read Currency|null $currency The current currency.
 * @property-read string $first_day The code of the first day of the week for the territory.
 * @property-read string $weekend_start The code of the first day of the weekend.
 * @property-read string $weekend_end The code of the last day of the weekend.
 * @property-read string|bool $language The ISO code of the official language, or `false` if it
 * cannot be determined.
 * @property-read string $name_as_* The name of the territory in the specified language.
 * @property-read int $population The population of the territory.
 *
 * @see http://www.unicode.org/reports/tr35/tr35-numbers.html#Supplemental_Currency_Data
 */
final class Territory
{
	/**
	 * @uses get_containment
	 * @uses get_currencies
	 * @uses get_currency
	 * @uses get_info
	 * @uses get_language
	 * @uses get_first_day
	 * @uses get_weekend_start
	 * @uses get_weekend_end
	 * @uses get_population
	 */
	use AccessorTrait;

	/**
	 * @phpstan-ignore-next-line
	 */
	private array $containment;

	/**
	 * @return array<string, mixed>
	 */
	private function get_containment(): array
	{
		return $this->containment ??= $this->retrieve_from_supplemental('territoryContainment');
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private array $currencies;

	/**
	 * @return array<string, mixed>
	 */
	private function get_currencies(): array
	{
		/** @phpstan-ignore-next-line */
		return $this->currencies ??= $this->repository->supplemental['currencyData']['region'][$this->code];
	}

	private ?Currency $currency;

	/**
	 * @throws Throwable
	 */
	private function get_currency(): ?Currency
	{
		return $this->currency ??= $this->currency_at();
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	private array $info;

	/**
	 * @return array<string, mixed>
	 */
	private function get_info(): array
	{
		return $this->info ??= $this->retrieve_from_supplemental('territoryInfo');
	}

	private string|false $language;

	/**
	 * Return the ISO code of the official language of the territory.
	 *
	 * @return string|false
	 *     The ISO code of the official language, or `false` if it cannot be determined.
	 */
	private function get_language(): string|false
	{
		$make = function () {
			$info = $this->get_info();

			foreach ($info['languagePopulation'] as $language => $lp)
			{
				if (empty($lp['_officialStatus']) || ($lp['_officialStatus'] != "official" && $lp['_officialStatus'] != "de_facto_official"))
				{
					continue;
				}

				return $language;
			}

			return false;
		};

		return $this->language ??= $make();
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
		$info = $this->get_info();

		return (int) $info['_population'];
	}

	public function __construct(
		public readonly Repository $repository,
		public readonly string $code
	) {
		$repository->territories->assert_defined($code);
	}

	public function __toString(): string
	{
		return $this->code;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $property)
	{
		if (str_starts_with($property, 'name_as_'))
		{
			$locale_code = substr($property, strlen('name_as_'));
			$locale_code = strtr($locale_code, '_', '-');

			return $this->name_as($locale_code);
		}

		return $this->accessor_get($property);
	}

	/**
	 * @return array<string, mixed>
	 */
	private function retrieve_from_supplemental(string $section): array
	{
		/** @phpstan-ignore-next-line */
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
		$code = $this->find_currency_at($this->get_currencies(), $date->format('Y-m-d'));

		if (!$code)
		{
			return null;
		}

		return new Currency($this->repository, $code);
	}

	/**
	 * Return the currency in a list used at a point in time.
	 *
	 * @param array<string, mixed> $currencies
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

			// @phpstan-ignore-next-line
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
		$containment = $this->get_containment();

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
		/** @phpstan-ignore-next-line */
		return $this->repository->locales[$locale_code]->localize($this);
	}

	private function resolve_week_data(string $which): string
	{
		$code = $this->code;
		/** @phpstan-ignore-next-line */
		$data = $this->repository->supplemental['weekData'][$which];

		return $data[$code] ?? $data['001'];
	}

	/**
	 * @param DateTimeInterface|string|null $datetime
	 *
	 * @throws Throwable
	 */
	private function ensure_is_datetime($datetime): DateTimeInterface
	{
		if ($datetime === null)
		{
			return new DateTimeImmutable();
		}

		if ($datetime instanceof DateTimeInterface)
		{
			return $datetime;
		}

		return new DateTimeImmutable($datetime);
	}
}
