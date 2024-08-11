<?php

namespace ICanBoogie\CLDR\Supplemental\Territory;

/**
 * A territory code.
 */
final class TerritoryCode
{
    /**
     * Whether a currency code is defined.
     *
     * @param string $code
     *     A currency code; for example, EUR.
     */
    public static function is_defined(string $code): bool
    {
        return in_array($code, TerritoryData::CODES);
    }

    /**
     * @param string $code
     *     A currency code; for example, EUR.
     *
     * @throws TerritoryNotDefined
     */
    public static function assert_is_defined(string $code): void
    {
        self::is_defined($code)
            or throw new TerritoryNotDefined($code);
    }

    /**
     * Returns a {@see TerritoryCode} of the specified code.
     *
     * @param string $code
     *     A currency code; for example, EUR.
     *
     * @throws TerritoryNotDefined
     */
    public static function of(string $code): self
    {
        static $instances;

        self::assert_is_defined($code);

        return $instances[$code] ??= new self($code);
    }

    /**
     * @param string $value
     *     A territory code; for example, CA.
    */
    private function __construct(
        public readonly string $value,
    ) {
    }

    /**
     * Returns the {@see $value} of the currency.
     */
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
        $this->value = $data['value'];
    }
}
