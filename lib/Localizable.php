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
 * An interface for classes whose instances can be localized.
 *
 * @template TSource of object
 * @template TLocalized of LocalizedObject
 */
interface Localizable
{
	/**
	 * Localize the source object.
	 *
	 * @param TSource $source
	 * @param Locale $locale
	 * @param array<string, mixed> $options
	 *
	 * @return LocalizedObject<TLocalized>
	 */
	static public function localize(object $source, Locale $locale, array $options = []): LocalizedObject;
}
