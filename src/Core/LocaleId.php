<?php

namespace ICanBoogie\CLDR\Core;

/**
 * Represents a Unicode Locale Identifier.
 *
 * @link https://unicode.org/reports/tr35/tr35-75/tr35.html#Unicode_locale_identifier
 */
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

    private const REGEXP_LOCALE_ID = <<<REGEXP
    /^
    (?P<language_id>(?:(?!-[utx]-).)*)
    (?:-u-(?P<unicode_ext>(?:(?!-[tx]-).)*))?
    (?:-t-(?P<transform_ext>(?:(?!-x-).)*))?
    (?:-x-(?P<private_ext>.*))?
    $/xi
    REGEXP;

    /**
     * Parses a Unicode Locale Identifier and returns a {@see LocaleId}.
     *
     * @param string $locale_id
     *     A Unicode Locale Identifier.
     *     {@link https://unicode.org/reports/tr35/tr35-75/tr35.html#Unicode_locale_identifier}
     */
    public static function parse(string $locale_id): LocaleId
    {
        preg_match(self::REGEXP_LOCALE_ID, $locale_id, $matches);

        $matches += [
            'language_id' => null,
            'unicode_ext' => null,
            'transform_ext' => null,
            'private_ext' => null,
        ];

        $language_id = $matches['language_id']
            ? LanguageId::parse($matches['language_id'])
            : throw new InvalidLocaleId("Malformed locale id: $locale_id");

        $unicode_ext = $matches['unicode_ext']
            ? UnicodeLocaleExtensions::parse($matches['unicode_ext'])
            : new UnicodeLocaleExtensions();

        $transform_ext = $matches['transform_ext']
            ? TransformLocaleExtensions::parse($matches['transform_ext'])
            : new TransformLocaleExtensions();

        return new self(
            value: $locale_id,
            available_id: self::resolve_usage_locale($language_id),
            language_id: $language_id,
            unicode_ext: $unicode_ext,
            transform_ext: $transform_ext,
        );
    }

    private const SEP = '-';

    /**
     * Resolves the locale to use when reading data from the CLDR.
     *
     * @return string
     *     An available locale.
     *
     * @throws LocaleNotAvailable
     *
     * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/availableLocales.json
     */
    private static function resolve_usage_locale(LanguageId $language_id): string
    {
        $l = $language_id->language;
        $s = $language_id->script;
        $r = $language_id->region;
        $v = implode(self::SEP, $language_id->variants);

        $tries = [
            "$l-$s-$r-$v",
            "$l-$s-$r",
            "$l-$s-$v",
            "$l-$s",
            "$l-$r-$v",
            "$l-$r",
            "$l-$v",
            "$l",
        ];

        $available_locales = array_combine(LocaleData::AVAILABLE_LOCALES, LocaleData::AVAILABLE_LOCALES);

        foreach ($tries as $try) {
            if (isset($available_locales[$try])) {
                return $try;
            }
        }

        throw new LocaleNotAvailable((string) $language_id);
    }

    /**
     * Returns the {@see LocaleId} of a value.
     *
     * @param string|LanguageId|LocaleId $value
     *     A Unicode Locale Identifier; for example, fr-BE
     *     If a {@see LocaleId} instance is provided it is returned as is.
     *
     * @throws LocaleNotAvailable
     */
    public static function from(string|LanguageId|LocaleId $value): self
    {
        static $instances;

        if ($value instanceof self) {
            return $value;
        }

        if ($value instanceof LanguageId) {
            return new self(
                value: (string) $value,
                available_id: self::resolve_usage_locale($value),
                language_id: $value,
            );
        }

        try {
            return $instances[$value] ??= self::parse($value);
        } catch (\Throwable $e) {
            throw new LocaleNotAvailable((string) $value, previous: $e);
        }
    }

    /**
     * Either the region override from th -u- extension of the region defined by the language ID.
     */
    public readonly string $final_region;

    /**
     * @param string $value
     *     A locale identifier; for example, fr-FR.
     *     As provided by the user.
     * @param string $available_id
     *     An available locale identifier; for example, fr.
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/availableLocales.json}
     */
    private function __construct(
        public readonly string $value,
        public readonly string $available_id,
        public readonly LanguageId $language_id,
        public readonly UnicodeLocaleExtensions $unicode_ext = new UnicodeLocaleExtensions(),
        public readonly TransformLocaleExtensions $transform_ext = new TransformLocaleExtensions(),
    ) {
        $this->final_region = $unicode_ext->region_override ?? $this->language_id->region;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function __serialize(): array
    {
        return [ 'value' => $this->value ];
    }

    /**
     * @param array{ value: string } $data
     */
    public function __unserialize(array $data): void
    {
        $id = self::parse($data['value']);

        $this->value = $id->value;
        $this->available_id = $id->available_id;
        $this->final_region = $id->final_region;
        $this->language_id = $id->language_id;
        $this->unicode_ext = $id->unicode_ext;
        $this->transform_ext = $id->transform_ext;
    }
}
