<?php

namespace ICanBoogie\CLDR;

/**
 * A localized locale.
 *
 * @property-read string $name
 *     The localized name of the locale.
 *
 * @extends LocalizedObject<Locale>
 */
class LocalizedLocale extends LocalizedObject
{
	/**
	 * @uses get_name
	 */
	protected function get_name(): string
	{
		/** @phpstan-ignore-next-line */
		return $this->locale['languages'][$this->target->id->value];
	}
}
