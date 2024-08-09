<?php

namespace ICanBoogie\CLDR;

use DateTimeImmutable;
use DateTimeInterface;
use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Territory\RegionCurrencies;
use Throwable;
use function ICanBoogie\trim_prefix;
use function in_array;

/**
 * A territory.
 *
 * @property-read array<string, mixed> $containment The `territoryContainment` data.
 * @property-read array<string, mixed> $info The `territoryInfo` data.
 * @property-read RegionCurrencies $currencies The currencies available in the territory.
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
 *
 * @implements Localizable<Territory, LocalizedTerritory>
 */
final class Territory implements Localizable
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

	private RegionCurrencies $currencies;

	private function get_currencies(): RegionCurrencies
	{
		return $this->currencies ??= RegionCurrencies::from(
			/** @phpstan-ignore-next-line */
			$this->repository->supplemental['currencyData']['region'][$this->code]
		);
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

			foreach ($info['languagePopulation'] as $language => $lp) {
				if (empty($lp['_officialStatus']) || ($lp['_officialStatus'] != "official" && $lp['_officialStatus'] != "de_facto_official")) {
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

		return (int)$info['_population'];
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
		if (str_starts_with($property, 'name_as_')) {
			$locale_id = trim_prefix($property, 'name_as_');
			$locale_id = strtr($locale_id, '_', '-');

			return $this->name_as($locale_id);
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
	 * @param DateTimeInterface|string|null $date
	 *
	 * @throws Throwable
	 */
	public function currency_at(DateTimeInterface|string $date = null): ?Currency
	{
		$date = $this->ensure_is_datetime($date)->format('Y-m-d');

		$code = $this->get_currencies()->at($date)?->code;

		if (!$code) {
			return null;
		}

		return Currency::of($code);
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
	public function name_as(string|LocaleId $locale_id): string
	{
		return $this->localized($locale_id)->name;
	}

	/**
	 * @return LocalizedTerritory
	 */
	public function localized(Locale|LocaleId|string $locale): LocalizedObject
	{
		if (!$locale instanceof Locale) {
			$locale = $this->repository->locale_for($locale);
		}

		return new LocalizedTerritory($this, $locale);
	}

	private function resolve_week_data(string $which): string
	{
		$code = $this->code;
		/** @phpstan-ignore-next-line */
		$data = $this->repository->supplemental['weekData'][$which];

		return $data[$code] ?? $data['001'];
	}

	private function ensure_is_datetime(DateTimeInterface|string|null $datetime): DateTimeInterface
	{
		if ($datetime === null) {
			return new DateTimeImmutable();
		}

		if ($datetime instanceof DateTimeInterface) {
			return $datetime;
		}

		return new DateTimeImmutable($datetime);
	}
}
