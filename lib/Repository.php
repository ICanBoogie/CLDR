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
 * @property-read ProviderInterface $provider
 * @property-read LocaleCollection $locales
 * @property-read Supplemental $supplemental
 * @property-read TerritoryCollection $territories
 * @property-read CurrencyCollection $currencies
 * @property-read NumberFormatter $number_formatter
 * @property-read ListFormatter $list_formatter
 *
 * @see http://www.unicode.org/repos/cldr-aux/json/24/
 */
class Repository
{
	use AccessorTrait;

	/**
	 * @var ProviderInterface
	 */
	private $provider;

	/**
	 * @return ProviderInterface
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
	 * @return ListFormatter
	 */
	protected function lazy_get_list_formatter()
	{
		return new ListFormatter($this);
	}

	/**
	 * Initializes the {@link $provider} property.
	 *
	 * @param ProviderInterface $provider
	 */
	public function __construct(ProviderInterface $provider)
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
}
