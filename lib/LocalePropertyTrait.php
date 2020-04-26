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
 * A trait for classes implementing the `locale` property.
 *
 * @property-read Locale $locale
 */
trait LocalePropertyTrait
{
	/**
	 * @var Locale
	 */
	private $locale;

	private function get_locale(): Locale
	{
		return $this->locale;
	}
}
