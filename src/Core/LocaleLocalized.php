<?php

namespace ICanBoogie\CLDR\Core;

/**
 * A localized locale.
 *
 * @extends LocalizedObject<Locale>
 */
class LocaleLocalized extends LocalizedObject
{
    /**
     * @var string The localized name of the locale.
     */
    public readonly string $name;

    public function __construct(Locale $target, Locale $locale)
    {
        $this->name = $locale['languages'][$target->id->value];

        parent::__construct($target, $locale);
    }
}
