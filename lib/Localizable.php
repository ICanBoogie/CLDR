<?php

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
