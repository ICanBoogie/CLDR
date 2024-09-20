<?php

namespace ICanBoogie\CLDR\Numbers;

use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\Localizable;

/**
 * Representation of a currency.
 *
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-numbers.html#Currencies
 *
 * @implements Localizable<Currency, CurrencyLocalized>
 */
final class Currency implements Localizable
{
    /**
     * Whether a currency code is defined.
     *
     * @param string $code
     *     A currency code; for example, EUR.
     */
    public static function is_defined(string $code): bool
    {
        return in_array($code, CurrencyData::CODES);
    }

    /**
     * @param string $code
     *     A currency code; for example, EUR.
     *
     * @throws CurrencyNotDefined
     */
    public static function assert_is_defined(string $code): void
    {
        self::is_defined($code)
            or throw new CurrencyNotDefined($code);
    }

    /**
     * Returns the {@see Currency} of the specified code.
     *
     * @param string $code
     *     A currency code; for example, EUR.
     *
     * @throws CurrencyNotDefined
     */
    public static function of(string $code): self
    {
        static $instances;

        self::assert_is_defined($code);

        return $instances[$code] ??= new self($code, self::fraction_for($code));
    }

    /**
     * Returns the {@see Fraction} for the specified currency code.
     *
     * @param string $code
     * *     A currency code; for example, EUR.
     */
    private static function fraction_for(string $code): Fraction
    {
        static $default_fraction;

        $data = CurrencyData::FRACTIONS[$code] ?? null;

        if (!$data) {
            return $default_fraction ??= self::fraction_for(CurrencyData::FRACTIONS_FALLBACK);
        }

        return Fraction::from($data);
    }

    /**
     * @param string $code
     *     A currency code; for example, EUR.
    */
    private function __construct(
        public readonly string $code,
        public readonly Fraction $fraction,
    ) {
    }

    /**
     * Returns the {@see $code} of the currency.
     */
    public function __toString(): string
    {
        return $this->code;
    }

    public function __serialize(): array
    {
        return [ 'code' => $this->code ];
    }

    /**
     * @param array{ code: string } $data
     */
    public function __unserialize(array $data): void
    {
        $this->code = $data['code'];
        $this->fraction = self::fraction_for($this->code);
    }

    /**
     * Returns a localized currency.
     */
    public function localized(Locale $locale): CurrencyLocalized
    {
        return new CurrencyLocalized($this, $locale);
    }
}
