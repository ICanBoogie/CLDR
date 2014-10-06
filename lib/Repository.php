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
 * </pre>
 *
 * @property-read Provider $provider A CLDR provider.
 * @property-read LocaleCollection $locales Locale collection.
 * @property-read Supplemental $supplemental Representation of the "supplemental" section.
 *
 * @see http://www.unicode.org/repos/cldr-aux/json/24/
 */
class Repository
{
	/**
	 * A CLDR provider.
	 *
	 * @var Provider
	 */
	protected $provider;

	/**
	 * Locale collection.
	 *
	 * @var LocaleCollection
	 */
	protected $locales;

	/**
	 * Representation of the "supplemental" section.
	 *
	 * @var Supplemental
	 */
	protected $supplemental;

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
		switch ($property)
		{
			case 'provider':     return $this->get_provider();
			case 'locales':      return $this->get_locales();
			case 'supplemental': return $this->get_supplemental();
		}

		throw new PropertyNotDefined(array($property, $this));
	}

	protected function get_provider()
	{
		return $this->provider;
	}

	protected function get_locales()
	{
		if ($this->locales)
		{
			return $this->locales;
		}

		return $this->locales = new LocaleCollection($this);
	}

	protected function get_supplemental()
	{
		if ($this->supplemental)
		{
			return $this->supplemental;
		}

		return $this->supplemental = new Supplemental($this);
	}

	/**
	 * Fetches the data available at the specified path.
	 *
	 * Note: The method is forwarded to {@link Provider::fetch}.
	 *
	 * @param string $path
	 */
	public function fetch($path)
	{
		return $this->provider->fetch($path);
	}
}