<?php

namespace ICanBoogie\CLDR\Core;

/**
 * An interface for classes whose instances can be localized.
 *
 * @template TSource of object
 * @template TLocalized of LocalizedObject
 */
interface Localizable
{
    /**
     * Localize the instance.
     *
     * @return LocalizedObject&LocalizedObject<TSource>
     */
    public function localized(Locale $locale): LocalizedObject;
}
