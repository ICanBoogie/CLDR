<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Numbers\Symbols;

use function str_replace;

/**
 * A currency formatter.
 *
 * @implements Localizable<CurrencyFormatter, LocalizedCurrencyFormatter>
 */
final class CurrencyFormatter implements Formatter, Localizable
{
    public const DEFAULT_CURRENCY_SYMBOL = '¤';

    public function __construct(
        private readonly NumberFormatter $number_formatter,
    ) {
    }

    /**
     * Formats a number with the specified pattern.
     *
     * @param float|int|numeric-string $number
     *     The number to format.
     * @param string|NumberPattern $pattern
     *     The pattern used to format the number.
     */
    public function format(
        float|int|string $number,
        NumberPattern|string $pattern,
        Symbols $symbols = null,
        string $currencySymbol = self::DEFAULT_CURRENCY_SYMBOL
    ): string {
        return str_replace(
            self::DEFAULT_CURRENCY_SYMBOL,
            $currencySymbol,
            $this->number_formatter->format($number, $pattern, $symbols)
        );
    }

    public function localized(Locale $locale): LocalizedCurrencyFormatter
    {
        return new LocalizedCurrencyFormatter($this, $locale);
    }
}
