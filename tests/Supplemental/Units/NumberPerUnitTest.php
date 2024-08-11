<?php

namespace Test\ICanBoogie\CLDR\Supplemental\Units;

use ICanBoogie\CLDR\Supplemental\Units\NumberPerUnit;
use ICanBoogie\CLDR\Supplemental\Units\UnitLength;
use ICanBoogie\CLDR\Supplemental\Units\Units;
use PHPUnit\Framework\TestCase;
use Test\ICanBoogie\CLDR\StringHelpers;

use function Test\ICanBoogie\CLDR\locale_for;

final class NumberPerUnitTest extends TestCase
{
    use StringHelpers;

    public function test_to_string(): void
    {
        $stu = new NumberPerUnit(123.4504, 'digital-gigabyte', 'duration-hour', $this->units_for('fr'));

        $this->assertSame("123,45 gigaoctets par heure", (string)$stu);
    }

    /**
     * @dataProvider provide_test_cases
     *
     * @param float|int|numeric-string $number
     */
    public function test_cases(
        string $locale,
        float|int|string $number,
        string $number_unit,
        string $per_unit,
        UnitLength $length,
        string $expected
    ): void {
        $stu = new NumberPerUnit($number, $number_unit, $per_unit, $this->units_for($locale));

        $this->assertSame($expected, $stu->{'as_' . $length->value});
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_cases(): array
    {
        return [

            [ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::LONG, "12.345 liters per hour" ],
            [ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::SHORT, "12.345 L/h" ],
            [ 'en', 12.345, 'volume-liter', 'duration-hour', UnitLength::NARROW, "12.345L/h" ],

            [ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::LONG, "12,345 litres par heure" ],
            [ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::SHORT, "12,345 l/h" ],
            [ 'fr', 12.345, 'volume-liter', 'duration-hour', UnitLength::NARROW, "12,345l/h" ],

            [ 'fr', 12.345, 'volume-liter', 'area-square-meter', UnitLength::LONG, "12,345 litres par mètre carré" ],
            [
                'fr',
                12.345,
                'angle-revolution',
                'length-light-year',
                UnitLength::LONG,
                "12,345 tours par années-lumière"
            ],

        ];
    }

    private function units_for(string $locale): Units
    {
        return locale_for($locale)->units;
    }
}
