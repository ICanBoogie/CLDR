<?php

namespace ICanBoogie\CLDR\General\Units;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\General\Units;

/**
 * @internal
 *
 * @property-read string $as_long A long format of the number.
 * @property-read string $as_short A short format of the number.
 * @property-read string $as_narrow A narrow format of the number.
 */
final class NumberWithUnit
{
    /**
     * @uses get_as_long
     * @uses get_as_short
     * @uses get_as_narrow
     */
    use AccessorTrait;

    /**
     * @param float|int|numeric-string $number
     */
    public function __construct(
        private readonly float|int|string $number,
        private readonly string $unit,
        private readonly Units $units
    ) {
    }

    public function __toString(): string
    {
        return $this->as(Units::DEFAULT_LENGTH);
    }

    public function per(Unit|string $unit): NumberPerUnit
    {
        return new NumberPerUnit($this->number, $this->unit, $unit, $this->units);
    }

    private function get_as_long(): string
    {
        return $this->as(UnitLength::LONG);
    }

    private function get_as_short(): string
    {
        return $this->as(UnitLength::SHORT);
    }

    private function get_as_narrow(): string
    {
        return $this->as(UnitLength::NARROW);
    }

    private function as(UnitLength $length): string
    {
        return $this->units->format($this->number, $this->unit, $length);
    }
}
