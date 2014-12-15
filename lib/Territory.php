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
use ICanBoogie\PropertyNotDefined;

/**
 * A territory.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read string $currency The current ISO 4217 currency code.
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
	/**
	 * @var Repository
	 */
	protected $repository;

	/**
	 * The ISO code of the territory.
	 *
	 * @var string
	 */
	protected $code;

	protected function get_code()
	{
		return $this->code;
	}

	/**
	 * Initialize the {@link $repository} and {@link $code} properties.
	 *
	 * @param Repository $repository The CLDR.
	 * @param string $code The ISO code of the territory.
	 */
	public function __construct(Repository $repository, $code)
	{
		$this->repository = $repository;
		$this->code = $code;
	}

	public function __get($property)
	{
		$method = 'get_' . $property;

		if (method_exists($this, $method))
		{
			return $this->$method();
		}

		if (strpos($property, 'name_as_') === 0)
		{
			$locale_code = substr($property, strlen('name_as_'));
			$locale_code = strtr($locale_code, '_', '-');

			return $this->name_as($locale_code);
		}

		throw new PropertyNotDefined(array( $property, $this ));
	}

	private $containment;

	protected function get_containment()
	{
		if ($this->containment)
		{
			return $this->containment;
		}

		$code = $this->code;
		$data = $this->repository->supplemental['territoryContainment'];

		if (empty($data[$code]))
		{
			throw new NoTerritoryData;
		}

		return $this->containment = $data[$code];
	}

	private $currencies;

	/**
	 * Returns the currencies used throughout the history of the territory.
	 *
	 * @return array
	 */
	protected function get_currencies()
	{
		if ($this->currencies)
		{
			return $this->currencies;
		}

		$code = $this->code;
		$data = $this->repository->supplemental['currencyData'];

		if (empty($data['region'][$code]))
		{
			throw new NoTerritoryData;
		}

		return $this->currencies = $data['region'][$code];
	}

	private $currency;

	protected function get_currency()
	{
		if ($this->currency)
		{
			return $this->currency;
		}

		return $this->currency = $this->currency_at();
	}

	/**
	 * Return the ISO 4217 code of the currency used in the territory at a point in time.
	 *
	 * @param \DateTime|mixed $date
	 *
	 * @return string
	 */
	public function currency_at($date=null)
	{
		if (!$date)
		{
			$date = 'now';
		}

		$date = DateTime::from($date)->as_date;
		$currencies = $this->get_currencies();
		$from = null;

		$rc = false;

		foreach ($currencies as $currency)
		{
			$name = key($currency);
			$interval = current($currency);

			if (isset($interval['_from']) && $interval['_from'] > $date)
			{
				continue;
			}

			if (isset($interval['_to']) && $interval['_to'] < $date)
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

	/*
	 *
	 */

	private $info;

	protected function get_info()
	{
		if ($this->info)
		{
			return $this->info;
		}

		$code = $this->code;
		$data = $this->repository->supplemental['territoryInfo'];

		if (empty($data[$code]))
		{
			throw new NoTerritoryData;
		}

		return $this->info = $data[$code];
	}

	private $language;

	/**
	 * Return the ISO code of the official language of the territory.
	 *
	 * @return string|bool The ISO code of the official language, or `false' if it cannot be
	 * determined.
	 */
	protected function get_language()
	{
		if ($this->language)
		{
			return $this->language;
		}

		$info = $this->get_info();

		foreach ($info['languagePopulation'] as $language => $lp)
		{
			if (empty($lp['_officialStatus']) || ($lp['_officialStatus'] != "official" && $lp['_officialStatus'] != "de_facto_official"))
			{
				continue;
			}

			return $this->language = $language;
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
		$info = $this->get_info();

		return (int) $info['_population'];
	}

	/**
	 * Whether the territory contains the specified territory.
	 *
	 * @param $code
	 *
	 * @return bool
	 */
	public function is_containing($code)
	{
		try
		{
			$containment = $this->get_containment();

			return in_array($code, $containment['_contains']);
		}
		catch (NoTerritoryData $e) {}

		return false;
	}

	public function name_as($locale_code)
	{
		$locale = $this->repository->locales[$locale_code];

		return $locale['territories'][$this->code];
	}
}
