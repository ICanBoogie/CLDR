<?php

namespace ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\Localizable;

class LocalizableSample implements Localizable
{
	static public function localize($source, Locale $locale, array $options = [])
	{
		return new LocalizedLocalizableSample($source, $locale, $options);
	}
}
