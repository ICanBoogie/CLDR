<?php

namespace ICanBoogie\CLDR\Core;

/**
 * @link https://www.unicode.org/reports/tr35/tr35-75/tr35.html#Unicode_language_identifier
 */
final class LanguageId
{
    private const REGEXP_LANGUAGE_ID = <<<REGEXP
    /^
    (?P<language>[a-z]{2,3}|[a-z]{5,8})
    (?:-(?P<script>[a-z]{4}))?
    (?:-(?P<region>[a-z]{2}|\d{3}))?
    (?P<variants>(?:-(?:[a-z\d]{5,8}|\d[a-z\d]{3}))+)?
    $/ix
    REGEXP;

    private const SEP = '-';

    /**
     * @param string $language_id
     *
     * @return self
     *     The language identifier is expanded with likely subtags.
     */
    public static function parse(string $language_id): self
    {
        if (!preg_match(self::REGEXP_LANGUAGE_ID, $language_id, $matches)) {
            throw new InvalidLanguageId("Unable to match language ID: $language_id");
        }

        $matches += [
            'language' => null,
            'script' => null,
            'region' => null,
            'variants' => null,
        ];

        $variants = $matches['variants']
            ? explode(self::SEP, substr($matches['variants'], 1))
            : [];

        return self::of(
            $matches['language'],
            $matches['script'] ?: null,
            $matches['region'] ?: null,
            $variants
        );
    }

    /**
     * @param string[] $variants
     */
    public static function of(
        ?string $language = null,
        ?string $script = null,
        ?string $region = null,
        array $variants = [],
    ): self {
        [ $language, $script, $region ] = LikelySubtags::add($language, $script, $region);

        sort($variants, SORT_NATURAL);

        return new self(
            language: $language,
            script: $script,
            region: $region,
            variants: $variants,
        );
    }

    /**
     * @param string[] $variants
     */
    public function __construct(
        public readonly string $language,
        public readonly string $script,
        public readonly string $region,
        public readonly array $variants = [],
    ) {
    }

    public function __toString(): string
    {
        $parts = [ $this->language, $this->script, $this->region, ...$this->variants ];

        return implode(self::SEP, $parts);
    }
}
