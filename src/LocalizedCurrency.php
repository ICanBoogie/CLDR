<?php

namespace ICanBoogie\CLDR;

/**
 * A localized currency.
 *
 * @extends LocalizedObjectWithFormatter<Currency, LocalizedCurrencyFormatter>
 */
final class LocalizedCurrency extends LocalizedObjectWithFormatter
{
    /**
     * @var string The localized name of the currency.
     */
    public readonly string $name;

    /**
     * @var string The localized symbol of the currency.
     */
    public readonly string $symbol;

    public function __construct(object $target, Locale $locale)
    {
        $l = $locale['currencies'][$target->code];
        $this->name = $l['displayName'];
        // Not all currencies have a symbol, we default to the currency code in those cases.
        $this->symbol = $l['symbol'] ?? $target->code;

        parent::__construct($target, $locale, $locale->currency_formatter);
    }

    /**
     * Returns the localized name of the currency.
     *
     * @param int|null $count Used for pluralization.
     */
    public function name_for(int $count = null): string
    {
        $offset = 'displayName';

        if ($count == 1) {
            $offset .= '-count-one';
        } elseif ($count) {
            $offset .= '-count-other';
        }

        return $this->locale['currencies'][$this->target->code][$offset];
    }

    /**
     * Formats currency using locale's conventions.
     *
     * @param float|int|numeric-string $number
     */
    public function format(
        float|int|string $number,
        string $pattern = LocalizedCurrencyFormatter::PATTERN_STANDARD
    ): string {
        return $this->formatter->format($number, $this->target, $pattern);
    }
}
