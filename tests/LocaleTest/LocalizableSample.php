<?php

namespace ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\Localizable;
use ICanBoogie\CLDR\LocalizedObject;

class LocalizableSample implements Localizable
{
	public static function localize(object $source, Locale $locale, array $options = []): LocalizedObject
	{
		return new LocalizedLocalizableSample($source, $locale, $options);
	}
}
