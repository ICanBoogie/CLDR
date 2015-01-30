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

use ICanBoogie\DateTime;

/**
 * A territory.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read array $containment The `territoryContainment` data.
 * @property-read array $info The `territoryInfo` data.
 * @property-read array $currencies The currencies available in the country.
 * @property-read string $currency The current currency.
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
class Territory
{
	use AccessorTrait;
	use RepositoryPropertyTrait;
	use CodePropertyTrait;

	/**
	 * Initialize the {@link $repository} and {@link $code} properties.
	 *
	 * @param Repository $repository
	 * @param string $code The ISO code of the territory.
	 */
	public function __construct(Repository $repository, $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	public function __get($property)
	{
		if (strpos($property, 'name_as_') === 0)
		{
			$locale_code = substr($property, strlen('name_as_'));
			$locale_code = strtr($locale_code, '_', '-');

			return $this->name_as($locale_code);
		}

		return $this->__object_get($property);
	}

	/**
	 * Retrieve a territory section from supplemental.
	 *
	 * @param string $section
	 *
	 * @return mixed
	 *
	 * @throws NoTerritoryData in attempt to retrieve data that is not defined for a territory.
	 */
	private function retrieve_from_supplemental($section)
	{
		$code = $this->code;
		$data = $this->repository->supplemental[$section];

		if (empty($data[$code]))
		{
			throw new NoTerritoryData;
		}

		return $data[$code];
	}

	/**
	 * Return the `territoryContainment` data.
	 *
	 * @return array
	 */
	protected function lazy_get_containment()
	{
		return $this->retrieve_from_supplemental('territoryContainment');
	}

	/**
	 * Returns the currencies used throughout the history of the territory.
	 *
	 * @return array
	 */
	protected function lazy_get_currencies()
	{
		$code = $this->code;
		$data = $this->repository->supplemental['currencyData'];

		if (empty($data['region'][$code]))
		{
			throw new NoTerritoryData;
		}

		return $data['region'][$code];
	}

	/**
	 * @return Currency
	 */
	protected function lazy_get_currency()
	{
		return $this->currency_at();
	}

	/**
	 * Return the currency used in the territory at a point in time.
	 *
	 * @param \DateTime|mixed $date
	 *
	 * @return Currency
	 */
	public function currency_at($date = null)
	{
		$code = $this->find_currency_at($this->currencies, DateTime::from($date ?: 'now')->as_date);

		if (!$code)
		{
			return null;
		}

		return new Currency($this->repository, $code);
	}

	/**
	 * Return the currency in a list used at a point in time.
	 *
	 * @param array $currencies
	 * @param string $normalized_date
	 *
	 * @return string
	 */
	private function find_currency_at(array $currencies, $normalized_date)
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

	/*
	 * weekData
	 */

	/**
	 * Returns week data.
	 *
	 * @param string $which Week data code.
	 *
	 * @return array
	 */
	private function get_week_data($which)
	{
		$code = $this->code;
		$data = $this->repository->supplemental['weekData'][$which];

		return empty($data[$code]) ? $data['001'] : $data[$code];
	}

	/**
	 * Return the code of the first day of the week.
	 *
	 * @return string
	 */
	protected function get_first_day()
	{
		return $this->get_week_data('firstDay');
	}

	/**
	 * Return the code of the first day of the weekend.
	 *
	 * @return string
	 */
	protected function get_weekend_start()
	{
		return $this->get_week_data('weekendStart');
	}

	/**
	 * Return the code of the last day of the weekend.
	 *
	 * @return string
	 */
	protected function get_weekend_end()
	{
		return $this->get_week_data('weekendEnd');
	}

	/**
	 * Return the `territoryInfo` data.
	 *
	 * @return array
	 */
	protected function lazy_get_info()
	{
		return $this->retrieve_from_supplemental('territoryInfo');
	}

	/**
	 * Return the ISO code of the official language of the territory.
	 *
	 * @return string|bool The ISO code of the official language, or `false' if it cannot be
	 * determined.
	 */
	protected function lazy_get_language()
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

	/**
	 * Return the population of the territory.
	 *
	 * @return int
	 */
	protected function get_population()
	{
		$info = $this->info;

		return (int) $info['_population'];
	}

	/**
	 * Whether the territory contains the specified territory.
	 *
	 * @param string $code
	 *
	 * @return bool
	 */
	public function is_containing($code)
	{
		try
		{
			$containment = $this->containment;

			return in_array($code, $containment['_contains']);
		}
		catch (NoTerritoryData $e)
		{
			#
			# If there is no territory data we just return false.
			#
		}

		return false;
	}

	/**
	 * Return the name of the territory localized according to the specified locale code.
	 *
	 * @param string $locale_code The ISO code of the locale.
	 *
	 * @return string
	 */
	public function name_as($locale_code)
	{
		return $this->localize($locale_code)->name;
	}

	/**
	 * Localize the currency.
	 *
	 * @param string $locale_code
	 *
	 * @return LocalizedCurrency
	 */
	public function localize($locale_code)
	{
		return $this->repository->locales[$locale_code]->localize($this);
	}
}
