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
 * Representation of a localized object.
 *
 * @property-read mixed $target The object to localize.
 * @property-read Locale $locale The locale used by the formatter.
 * @property-read mixed $formatter The formatter used to format the target object.
 */
abstract class LocalizedObject
{
	/**
	 * Creates a localized instance from the specified source and location.
	 *
	 * @param $source
	 * @param Locale $locale
	 * @param array $options
	 *
	 * @return LocalizedObject A localized instance.
	 */
	static public function from($source, Locale $locale, array $options=[])
	{
		return new static($source, $locale, $options);
	}

	use AccessorTrait;
	use LocalePropertyTrait;

	/**
	 * The object to localize.
	 *
	 * @var mixed
	 */
	protected $target;

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
	 * Initializes the {@link $target], {@link $locale}, and {@link $options} properties.
	 *
	 * @param mixed $target The object to localize.
	 * @param Locale $locale The locale used by the formatter.
	 * @param array $options Some options.
	 */
	public function __construct($target, Locale $locale, array $options=[])
	{
		$this->target = $target;
		$this->locale = $locale;
		$this->options = $options;
	}
}
