<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\Currency;
use ICanBoogie\CLDR\CurrencyNotDefined;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    #[DataProvider("provide_is_defined")]
    public function test_is_defined(string $code, bool $expected): void
    {
        $actual = Currency::is_defined($code);

        $this->assertSame($expected, $actual);
    }

    public static function provide_is_defined(): array
    {
        return [

            [ 'PES', true ],
            [ 'UAH', true ],
            [ 'ZZZ', false ],

        ];
    }

    #[DataProvider("provide_is_defined")]
    public function test_assert_is_defined(string $code, bool $defined): void
    {
        if (!$defined) {
            $this->expectException(CurrencyNotDefined::class);
        } else {
            $this->assertTrue(true);
        }

        Currency::assert_is_defined($code);
    }

    #[DataProvider("provide_is_defined")]
    public function test_of(string $code, bool $defined): void
    {
        if (!$defined) {
            $this->expectException(CurrencyNotDefined::class);
        }

        $actual = Currency::of($code);

        $this->assertEquals($code, $actual->code);
    }

    #[DataProvider('provide_fraction_properties')]
    public function test_fraction_properties(string $code, string $property, int $expected): void
    {
        $currency = Currency::of($code);

        $this->assertSame($expected, $currency->fraction->$property);
    }

    public static function provide_fraction_properties(): array
    {
        return [

            [ 'EUR', 'digits', 2 ],
            [ 'EUR', 'rounding', 0 ],
            [ 'EUR', 'cash_digits', 2 ],
            [ 'EUR', 'cash_rounding', 0 ],

            [ 'HUF', 'digits', 2 ],
            [ 'HUF', 'rounding', 0 ],
            [ 'HUF', 'cash_digits', 0 ],
            [ 'HUF', 'cash_rounding', 0 ],

            [ 'LYD', 'digits', 3 ],
            [ 'LYD', 'rounding', 0 ],
            [ 'LYD', 'cash_digits', 3 ],
            [ 'LYD', 'cash_rounding', 0 ],

            [ 'DKK', 'digits', 2 ],
            [ 'DKK', 'rounding', 0 ],
            [ 'DKK', 'cash_digits', 2 ],
            [ 'DKK', 'cash_rounding', 50 ],

        ];
    }

    public function test_serialization(): void
    {
        $sut = Currency::of('EUR');
        $actual = unserialize(serialize($sut));

        $this->assertEquals($sut, $actual);
    }

    public function test_localize(): void
    {
        $sut = Currency::of('EUR');
        $localized = $sut->localized(locale_for('fr'));

        $actual = $localized->format(12345.67);

        $this->assertEquals('12 345,67 €', $actual);
    }
}
