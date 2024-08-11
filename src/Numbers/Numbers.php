<?php

namespace ICanBoogie\CLDR\Numbers;

use ArrayObject;
use ICanBoogie\CLDR\Core\Locale;

/**
 * Numbers for a locale.
 *
 * @extends ArrayObject<string, mixed>
 *
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-numbers.html#1-numbering-systems
 */
final class Numbers extends ArrayObject
{
    public readonly Symbols $symbols;

    /**
     * Indicates which numbering system should be used for presentation of numeric quantities in the given locale.
     */
    public readonly string $default_numbering_system;

    /**
     * @phpstan-ignore-next-line
     */
    public readonly array $decimal_formats;

    /**
     * The standard decimal format of the default numbering system; for example, "#,##0.###".
     *
     * Shortcut to `decimalFormats-numberSystem-$default_numbering_system/standard`.
     */
    public readonly string $standard_decimal_format;

    /**
     * Shortcut to `decimalFormats-numberSystem-$default_numbering_system/short/decimalFormats`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $short_decimal_formats;

    /**
     * Shortcut to `decimalFormats-numberSystem-$default_numbering_system/long/decimalFormats`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $long_decimal_formats;

    /**
     * Shortcut to `scientificFormats-numberSystem-$default_numbering_system`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $scientific_formats;

    /**
     * Shortcut to `percentFormats-numberSystem-$default_numbering_system`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $percent_formats;

    /**
     * Shortcut to `currencyFormats-numberSystem-$default_numbering_system`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $currency_formats;

    /**
     * Shortcut to `miscPatterns-numberSystem-$default_numbering_system`.
     *
     * @phpstan-ignore-next-line
     */
    public readonly array $misc_patterns;

    /**
     * @param array<string, mixed> $data
     *
     * @link https://github.com/unicode-org/cldr-json/blob/45.0.0/cldr-json/cldr-numbers-full/main/en-001/numbers.json
     */
    public function __construct(
        public readonly Locale $locale,
        array $data
    ) {
        parent::__construct($data);

        $this->default_numbering_system = $dns = $data['defaultNumberingSystem'];
        $this->decimal_formats = $data["decimalFormats-numberSystem-$dns"];
        $this->standard_decimal_format = $this->decimal_formats['standard'];
        $this->short_decimal_formats = $this->decimal_formats['short']['decimalFormat'];
        $this->long_decimal_formats = $this->decimal_formats['long']['decimalFormat'];
        $this->scientific_formats = $data["scientificFormats-numberSystem-$dns"];
        $this->percent_formats = $data["percentFormats-numberSystem-$dns"];
        $this->currency_formats = $data["currencyFormats-numberSystem-$dns"];
        $this->misc_patterns = $data["miscPatterns-numberSystem-$dns"];
        $this->symbols = Symbols::from($data["symbols-numberSystem-$dns"]);
    }
}
