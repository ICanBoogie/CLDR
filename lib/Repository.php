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

use ICanBoogie\PropertyNotDefined;

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
 * @property-read Provider $provider A CLDR provider.
 * @property-read LocaleCollection $locales Locale collection.
 * @property-read Supplemental $supplemental Representation of the "supplemental" section.
 * @property-read TerritoryCollection $territories Territory collection.
 * @property-read CurrencyCollection $currencies Currency collection.
 *
 * @see http://www.unicode.org/repos/cldr-aux/json/24/
 */
class Repository
{
	/**
	 * @var Provider
	 */
	private $provider;

	protected function get_provider()
	{
		return $this->provider;
	}

	/**
	 * @var LocaleCollection
	 */
	private $locales;

	protected function get_locales()
	{
		if ($this->locales)
		{
			return $this->locales;
		}

		return $this->locales = new LocaleCollection($this);
	}

	/**
	 * @var Supplemental
	 */
	private $supplemental;

	protected function get_supplemental()
	{
		if ($this->supplemental)
		{
			return $this->supplemental;
		}

		return $this->supplemental = new Supplemental($this);
	}

	/**
	 * @var TerritoryCollection
	 */
	private $territories;

	protected function get_territories()
	{
		if ($this->territories)
		{
			return $this->territories;
		}

		return $this->territories = new TerritoryCollection($this);
	}

	private $currencies;

	protected function get_currencies()
	{
		if (!$this->currencies)
		{
			$this->currencies = new CurrencyCollection($this);
		}

		return $this->currencies;
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

	public function __get($property)
	{
		$method = 'get_' . $property;

		if (method_exists($this, $method))
		{
			return $this->$method();
		}

		throw new PropertyNotDefined(array($property, $this));
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * Note: The method is forwarded to {@link Provider::fetch}.
	 *
	 * @param string $path
	 *
	 * @return array
	 */
	public function fetch($path)
	{
		return $this->provider->fetch($path);
	}
}
