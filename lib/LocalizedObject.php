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
 * Representation of a localized object.
 *
 * @property-read mixed $target The object to localize.
 * @property-read Locale $locale The locale used by the formatter.
 * @property-read mixed $formatter The formatter used to format the target object.
 */
abstract class LocalizedObject
{
	static public function from($source, Locale $locale, array $options=[])
	{
		return new static($source, $locale, $options);
	}

	/**
	 * The object to localize.
	 *
	 * @var mixed
	 */
	protected $target;

	/**
	 * The locale used by the formatter.
	 *
	 * @var Locale
	 */
	protected $locale;

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

	/**
	 * The formatter used ot format the target object.
	 *
	 * @var mixed
	 */
	private $formatter;

	/**
	 * Support for the {@link $target}, {@link $locale}, and {@link $formatter} properties.
	 *
	 * @param string $property
	 *
	 * @throws PropertyNotDefined in attempt to get a property that is not supported.
	 *
	 * @return mixed
	 */
	public function __get($property)
	{
		switch ($property)
		{
			case 'target':

				return $this->target;

			case 'locale':

				return $this->locale;

			case 'formatter':

				if (!$this->formatter)
				{
					$this->formatter = $this->get_formatter();
				}

				return $this->formatter;
		}

		$method = 'get_' . $property;

		if (method_exists($this, $method))
		{
			return $this->$method();
		}

		throw new PropertyNotDefined([ $property, $this ]);
	}

	/**
	 * Returns the formatter to use to format the target object.
	 *
	 * @return mixed
	 */
	abstract protected function get_formatter();
}
