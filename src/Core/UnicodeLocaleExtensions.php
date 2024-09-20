<?php

namespace ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\BCP47\BCP47Data;
use ICanBoogie\CLDR\Supplemental\Territory\TerritoryData;

final class UnicodeLocaleExtensions
{
    private const MAP_PREFIX_TO_PROPERTY = [
        'ca' => 'calendar',
        'cf' => 'currency_format',
        'co' => 'collation',
        'cu' => 'currency',
        'dx' => 'dictionary_break_script_exclusions',
        'em' => 'emoji',
        'fw' => 'first_day_of_week',
        'hc' => 'hour_cycle',
        'lb' => 'line_break_style',
        'lw' => 'line_break_word_handling',
        'ms' => 'measurement_system',
        'mu' => 'measurement_unit_override',
        'nu' => 'numbering_system',
        'rg' => 'region_override',
        'sd' => 'regional_subdivision',
        'ss' => 'sentence_break_suppressions',
        'tz' => 'time_zone',
        'va' => 'common_variant',
    ];

    private const PREFIX_FOR_DICTIONARY_BREAK_SCRIPT_EXCLUSIONS = 'dx';
    private const PREFIX_FOR_REGION_OVERRIDE = 'rg';
    private const PREFIX_FOR_REGIONAL_SUBDIVISION = 'sd';

    private const REGEXP_EXTENSION = '/([a-z]{2})-((?:(?!-[a-z]{2}-).)+)/';

    /**
     * @param string $unicode_ext
     *     A Unicode Locale Extension (-u-) format (defined in BCP 47).
     *
     * @link https://unicode.org/reports/tr35/tr35.html#u_Extension
     * @link https://unicode.org/reports/tr35/tr35.html#Key_And_Type_Definitions_
     */
    public static function parse(string $unicode_ext): self
    {
        if (str_starts_with($unicode_ext, 'u-')) {
            $unicode_ext = substr($unicode_ext, 2);
        }

        preg_match_all(self::REGEXP_EXTENSION, $unicode_ext, $matches, PREG_SET_ORDER);

        $properties = [];

        foreach ($matches as [, $prefix, $value]) {
            $property = self::MAP_PREFIX_TO_PROPERTY[$prefix]
                ?? throw new InvalidUnicodeLocaleExtensions(
                    "Invalid extension prefix '$prefix' in '$unicode_ext'",
                );

            $value = match ($prefix) {
                self::PREFIX_FOR_DICTIONARY_BREAK_SCRIPT_EXCLUSIONS => explode('-', $value),
                self::PREFIX_FOR_REGION_OVERRIDE => self::parse_region_override($value),
                self::PREFIX_FOR_REGIONAL_SUBDIVISION => self::parse_regional_subdivision($value),
                default => $value,
            };

            $properties[$property] = $value;
        }

        return new self(...$properties);
    }

    private static function parse_region_override(string $value): string
    {
        $region = strtoupper(rtrim($value, 'z'));

        return $region;
    }

    private static function parse_regional_subdivision(string $value): string
    {
        throw new \RuntimeException("prefix not implemented yet: sd");
    }

    /**
     * @param string[] $dictionary_break_script_exclusions
     */
    public function __construct(
        public readonly ?string $calendar = null,
        public readonly ?string $currency_format = null,
        public readonly ?string $collation = null,
        public readonly ?string $currency = null,
        public readonly array $dictionary_break_script_exclusions = [],
        public readonly ?string $emoji = null,
        public readonly ?string $first_day_of_week = null,
        public readonly ?string $hour_cycle = null,
        public readonly ?string $line_break_style = null,
        public readonly ?string $line_break_word_handling = null,
        public readonly ?string $measurement_system = null,
        public readonly ?string $measurement_unit_override = null,
        public readonly ?string $numbering_system = null,
        public readonly ?string $region_override = null,
        public readonly ?string $regional_subdivision = null,
        public readonly ?string $sentence_break_suppressions = null,
        public readonly ?string $time_zone = null,
        public readonly ?string $common_variant = null,
    ) {
        $this->validate();
    }

    public function validate(): void
    {
        $err = [];

        foreach ($this->to_array() as $prefix => $value) {
            if ($value === null) {
                continue;
            }

            if ($prefix === self::PREFIX_FOR_DICTIONARY_BREAK_SCRIPT_EXCLUSIONS) {
                // TODO: validate scripts
                continue;
            }

            if ($prefix === self::PREFIX_FOR_REGION_OVERRIDE) {
                if (!in_array($value, TerritoryData::CODES)) {
                    $err[] = "$prefix: $value";
                }

                continue;
            }

            if (!in_array($value, BCP47Data::U_MAPPING[$prefix])) {
                $err[] = "$prefix: $value";
            }
        }

        if ($err) {
            throw new InvalidUnicodeLocaleExtensions("Invalid unicode extension: " . implode("; ", $err));
        }
    }

    /**
     * @return array<string, ?string>
     */
    public function to_array(): array
    {
        return [

            'ca' => $this->calendar,
            'cf' => $this->currency_format,
            'co' => $this->collation,
            'cu' => $this->currency,
            'dx' => $this->dictionary_break_script_exclusions,
            'em' => $this->emoji,
            'fw' => $this->first_day_of_week,
            'hc' => $this->hour_cycle,
            'lb' => $this->line_break_style,
            'lw' => $this->line_break_word_handling,
            'ms' => $this->measurement_system,
            'mu' => $this->measurement_unit_override,
            'nu' => $this->numbering_system,
            'rg' => $this->region_override,
            'sd' => $this->regional_subdivision,
            'ss' => $this->sentence_break_suppressions,
            'tz' => $this->time_zone,
            'va' => $this->common_variant,

        ];
    }
}
