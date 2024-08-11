<?php

namespace ICanBoogie\CLDR\Supplemental\Territory;

use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\LocalizedObject;

/**
 * A localized territory.
 *
 * @extends LocalizedObject<Territory>
 */
class TerritoryLocalized extends LocalizedObject
{
    /**
     * @var string The localized name of the territory.
     */
    public readonly string $name;

    public function __construct(Territory $target, Locale $locale)
    {
        $this->name = $locale['territories'][$target->code->value];

        parent::__construct($target, $locale);
    }
}
