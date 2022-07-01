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
 * @template T of object
 *
 * @property-read Locale $locale The locale used by the formatter.
 */
abstract class LocalizedObject
{
	/**
	 * @uses get_target
	 * @uses get_locale
	 */
	use AccessorTrait;
	use LocalePropertyTrait;

	/**
	 * Creates a localized instance from the specified source and location.
	 *
	 * @param T $source
	 * @param Locale $locale
	 * @param array<string, mixed> $options
	 *
	 * @return static<T>
	 */
	static public function from($source, Locale $locale, array $options = []): LocalizedObject
	{
		return new static($source, $locale, $options); // @phpstan-ignore-line
	}

	/**
	 * The object to localize.
	 *
	 * @var T
	 * @readonly
	 */
	public $target;

	/**
	 * @var array<string, mixed>
	 */
	protected $options;

	/**
	 * @param T $target The object to localize.
	 * @param array<string, mixed> $options
	 */
	public function __construct($target, Locale $locale, array $options = [])
	{
		$this->target = $target;
		$this->locale = $locale;
		$this->options = $options;
	}
}
