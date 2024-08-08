<?php

namespace Test\ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\Localizable;
use ICanBoogie\CLDR\LocalizedObject;

/**
 * @implements Localizable<object, LocalizedLocalizableSample>
 */
class LocalizableSample implements Localizable
{
	public static function localize(object $source, Locale $locale, array $options = []): LocalizedObject
	{
		// @phpstan-ignore-next-line
		return new LocalizedLocalizableSample($source, $locale, $options);
	}
}
