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

/**
 * Representation of a localized object.
 *
 * @property-read object $target The object to localize.
 * @property-read Locale $locale The locale used by the formatter.
 */
abstract class LocalizedObject
{
	use AccessorTrait;
	use LocalePropertyTrait;

	/**
	 * Creates a localized instance from the specified source and location.
	 *
	 * @param object $source
	 * @param Locale $locale
	 * @param array $options
	 *
	 * @return LocalizedObject A localized instance.
	 */
	static public function from($source, Locale $locale, array $options = [])
	{
		return new static($source, $locale, $options);
	}

	/**
	 * The object to localize.
	 *
	 * @var object
	 */
	protected $target;

	/**
	 * @return object
	 */
	protected function get_target()
	{
		return $this->target;
	}

	/**
	 * Options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Initializes the {@link $target}, {@link $locale}, and {@link $options} properties.
	 *
	 * @param object $target The object to localize.
	 * @param Locale $locale The locale used by the formatter.
	 * @param array $options Some options.
	 */
	public function __construct($target, Locale $locale, array $options = [])
	{
		$this->target = $target;
		$this->locale = $locale;
		$this->options = $options;
	}
}
