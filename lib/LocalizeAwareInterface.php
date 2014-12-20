<?php

namespace ICanBoogie\CLDR;

/**
 * An interface for classes whose instances can be localized.
 *
 * @package ICanBoogie\CLDR
 */
interface LocalizeAwareInterface
{
	/**
	 * Localize the source object.
	 *
	 * @param object $source
	 * @param Locale $locale
	 * @param array $options
	 *
	 * @return LocalizedObject
	 */
	static public function localize($source, Locale $locale, array $options=[]);
}
