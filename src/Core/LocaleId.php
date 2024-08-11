<?php

namespace ICanBoogie\CLDR\Core;

final class LocaleId
{
    /**
     * Whether a locale ID is available.
     *
     * @param string $value
     *     A locale identifier; for example, fr-BE
     */
    public static function is_available(string $value): bool
    {
        if (isset(LocaleData::PARENT_LOCALES[$value])) {
            return true;
        }

        return in_array($value, LocaleData::AVAILABLE_LOCALES);
    }

    /**
     * @param string $value
     *     A locale identifier; for example, fr-BE
     *
     * @throws LocaleNotAvailable
     */
    public static function assert_is_available(string $value): void
    {
        self::is_available($value)
            or throw new LocaleNotAvailable($value);
    }

    /**
     * Returns a {@see LocaleId} of a value.
     *
     * Note: If the locale has a parent locale, that locale is used instead.
     *
     * @param string $value
     *     A locale identifier; for example, fr-BE
     *
     * @throws LocaleNotAvailable
     */
    public static function of(string $value): self
    {
        static $instances;

        self::assert_is_available($value);

        if (isset(LocaleData::PARENT_LOCALES[$value])) {
            $value = LocaleData::PARENT_LOCALES[$value];
        }

        return $instances[$value] ??= new self($value);
    }

    /**
     * @param string $value
     *     A locale identifier; for example, fr-BE.
     */
    private function __construct(
        public readonly string $value,
    ) {
    }
}
