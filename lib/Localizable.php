<?php

namespace ICanBoogie\CLDR;

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
     * @return TLocalized&LocalizedObject<TSource>
     */
    public function localized(Locale $locale): LocalizedObject;
}
