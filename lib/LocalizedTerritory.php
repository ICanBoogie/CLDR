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
 * @property-read string $name
 *     The localized name of the territory.
 *
 * @extends LocalizedObject<Territory>
 */
class LocalizedTerritory extends LocalizedObject
{
	/**
	 * @uses get_name
	 */
	protected function get_name(): string
	{
		return $this->locale['territories'][$this->target->code];
	}
}
