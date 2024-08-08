<?php

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
		/** @phpstan-ignore-next-line */
		return $this->locale['territories'][$this->target->code];
	}
}
