<?php

namespace ICanBoogie\CLDR\Core;

/**
 * @link https://www.unicode.org/reports/tr35/tr35-75/tr35.html#BCP47_T_Extension
 */
final class TransformLocaleExtensions
{
    private const MAP_PREFIX_TO_PROPERTY = [
        'm0' => 'transform_extension_mechanism',
        's0' => 'transform_source',
        'd0' => 'transform_destination',
        'i0' => 'input_method_engine_transform',
        'k0' => 'keyboard_transform',
        't0' => 'machine_translation',
        'h0' => 'hybrid_locale_identifiers',
        'x0' => 'private_use',
    ];

    private const REGEXP_EXTENSION = '/([msdikthx]0)-([a-z\d]{3,8})/';

    /**
     * @param string $transform_ext
     *     A Transform Locale Extension (-t-) format (defined in BCP 47).
     */
    public static function parse(string $transform_ext): self
    {
        if (str_starts_with($transform_ext, 't-')) {
            $transform_ext = substr($transform_ext, 2);
        }

        preg_match_all(self::REGEXP_EXTENSION, $transform_ext, $matches, PREG_SET_ORDER);

        $properties = [];

        foreach ($matches as [, $prefix, $value]) {
            $property = self::MAP_PREFIX_TO_PROPERTY[$prefix]
                ?? throw new InvalidTransformLocaleExtensions(
                    "Invalid extension prefix '$prefix' in '$transform_ext'",
                );

            $properties[$property] = $value;
        }

        return new self(...$properties);
    }

    /**
     * @param string|null $transform_extension_mechanism
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform.json}
     * @param string|null $transform_source
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform-destination.json}
     * @param string|null $transform_destination
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform-destination.json}
     * @param string|null $input_method_engine_transform
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform_ime.json}
     * @param string|null $keyboard_transform
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform_keyboard.json}
     * @param string|null $machine_translation
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform_mt.json}
     * @param string|null $hybrid_locale_identifiers
     *     {@link
     *     https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform_hybrid.json}
     * @param string|null $private_use
     *     {@link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-bcp47/bcp47/transform_private_use.json}
     */
    public function __construct(
        public readonly ?string $transform_extension_mechanism = null,
        public readonly ?string $transform_source = null,
        public readonly ?string $transform_destination = null,
        public readonly ?string $input_method_engine_transform = null,
        public readonly ?string $keyboard_transform = null,
        public readonly ?string $machine_translation = null,
        public readonly ?string $hybrid_locale_identifiers = null,
        public readonly ?string $private_use = null,
    ) {
    }

    /**
     * @return array<string, ?string>
     */
    public function to_array(): array
    {
        return [

            'm0' => $this->transform_extension_mechanism,
            's0' => $this->transform_source,
            'd0' => $this->transform_destination,
            'i0' => $this->input_method_engine_transform,
            'k0' => $this->keyboard_transform,
            't0' => $this->machine_translation,
            'h0' => $this->hybrid_locale_identifiers,
            'x0' => $this->private_use,

        ];
    }
}
