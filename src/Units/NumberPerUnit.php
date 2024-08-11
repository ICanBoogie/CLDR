<?php

namespace ICanBoogie\CLDR\Units;

use ICanBoogie\Accessor\AccessorTrait;

/**
 * @internal
 *
 * @property-read string $as_long A long format of the number.
 * @property-read string $as_short A short format of the number.
 * @property-read string $as_narrow A narrow format of the number.
 */
final class NumberPerUnit
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
        private readonly string $number_unit,
        private readonly string $per_unit,
        private readonly Units $units
    ) {
    }

    public function __toString(): string
    {
        return $this->as(Units::DEFAULT_LENGTH);
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
        return $this->units->format_compound(
            $this->number,
            $this->number_unit,
            $this->per_unit,
            $length
        );
    }
}
