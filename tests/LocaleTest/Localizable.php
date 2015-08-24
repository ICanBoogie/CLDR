<?php

namespace ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocalizeAwareInterface;

class Localizable implements LocalizeAwareInterface
{
	static public function localize($source, Locale $locale, array $options = [])
	{
		return new LocalizedLocalizable($source, $locale, $options);
	}
}
