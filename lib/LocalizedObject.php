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

	/**
	 * Creates a localized instance from the specified source and location.
	 *
	 * @param T $source
	 * @param Locale $locale
	 * @param array<string, mixed> $options
	 *
	 * @return static<T>
	 */
	static public function from(object $source, Locale $locale, array $options = []): LocalizedObject
	{
		return new static($source, $locale, $options); // @phpstan-ignore-line
	}

	/**
	 * @param T $target The object to localize.
	 * @param array<string, mixed> $options
	 */
	public function __construct(
		public readonly object $target,
		public readonly Locale $locale,
		protected readonly array $options = []
	) {
	}
}
