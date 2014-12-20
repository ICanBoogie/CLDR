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
 * A localized locale
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read Locale $target
 * @property-read string $name The localized name of the locale.
 */
class LocalizedLocale extends LocalizedObject
{
	protected function get_name()
	{
		return $this->locale['languages'][$this->target->code];
	}
}
