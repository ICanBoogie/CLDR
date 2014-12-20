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
 * A localized territory.
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read Territory $target
 * @property-read string $name The localized name of the territory.
 */
class LocalizedTerritory extends LocalizedObject
{
	protected function get_name()
	{
		return $this->locale['territories'][$this->target->code];
	}
}
