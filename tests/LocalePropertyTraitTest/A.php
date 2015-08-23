<?php

namespace ICanBoogie\CLDR\LocalePropertyTraitTest;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocalePropertyTrait;

class A
{
	use AccessorTrait;
	use LocalePropertyTrait;

	public function __construct(Locale $locale)
	{
		$this->locale = $locale;
	}
}
