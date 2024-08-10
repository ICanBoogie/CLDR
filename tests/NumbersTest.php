<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\Numbers;
use ICanBoogie\CLDR\Numbers\Symbols;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumbersTest extends TestCase
{
    #[DataProvider('provide_test_shortcuts')]
    public function test_shortcuts(string $locale_id, string $property, string $offset): void
    {
        $locale = locale_for($locale_id);
        /** @var array<string, mixed> $numbers_data */
        $numbers_data = $locale['numbers'];
        $numbers = new Numbers($locale, $numbers_data);

        $this->assertSame($numbers_data[$offset], $numbers->$property);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_shortcuts(): array
    {
        return [

            [ 'fr', 'decimal_formats', 'decimalFormats-numberSystem-latn' ],
            [ 'fr', 'scientific_formats', 'scientificFormats-numberSystem-latn' ],
            [ 'fr', 'percent_formats', 'percentFormats-numberSystem-latn' ],
            [ 'fr', 'currency_formats', 'currencyFormats-numberSystem-latn' ],
            [ 'fr', 'misc_patterns', 'miscPatterns-numberSystem-latn' ]

        ];
    }

    #[DataProvider('provide_symbols')]
    public function test_symbols(string $locale_id, Symbols $expected): void
    {
        $locale = locale_for($locale_id);
        /** @var array<string, mixed> $numbers_data */
        $numbers_data = $locale['numbers'];
        $numbers = new Numbers($locale, $numbers_data);

        $this->assertEquals($expected, $numbers->symbols);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_symbols(): array
    {
        return [

            [
                'fr',
                new Symbols(
                    ',',
                    ' ',
                    ';',
                    '%',
                    '-',
                    '+',
                    '≃',
                    'E',
                    '×',
                    '‰',
                    '∞',
                    'NaN',
                    '.',
                    ',',
                    ':'
                )
            ],
            [
                'en',
                new Symbols(
                    '.',
                    ',',
                    ';',
                    '%',
                    '-',
                    '+',
                    '~',
                    'E',
                    '×',
                    '‰',
                    '∞',
                    'NaN',
                    '.',
                    ',',
                    ':'
                )
            ],
            [
                'ru',
                new Symbols(
                    ',',
                    ' ',
                    ';',
                    '%',
                    '-',
                    '+',
                    '≈',
                    'E',
                    '×',
                    '‰',
                    '∞',
                    'не число',
                    '.',
                    ',',
                    ':'
                )
            ],
        ];
    }

    #[DataProvider('provide_test_decimal_width_shortcuts')]
    public function test_decimal_width_shortcuts(
        string $locale_id,
        string $property,
        string $offset,
        string $width_offset
    ): void {
        $locale = locale_for($locale_id);
        /** @var array<string, mixed> $numbers_data */
        $numbers_data = $locale['numbers'];
        $numbers = new Numbers($locale, $numbers_data);

        $this->assertSame($numbers_data[$offset][$width_offset]['decimalFormat'], $numbers->$property);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_decimal_width_shortcuts(): array
    {
        return [

            [ 'fr', 'short_decimal_formats', 'decimalFormats-numberSystem-latn', 'short' ],
            [ 'fr', 'long_decimal_formats', 'decimalFormats-numberSystem-latn', 'long' ]

        ];
    }

    #[DataProvider('provide_test_get_decimal_format')]
    public function test_get_decimal_format(string $locale_id, string $expected): void
    {
        $locale = locale_for($locale_id);
        /** @var array<string, mixed> $numbers_data */
        $numbers_data = $locale['numbers'];
        $numbers = new Numbers($locale, $numbers_data);

        $this->assertEquals($expected, $numbers->standard_decimal_format);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_decimal_format(): array
    {
        return [

            [ 'en', "#,##0.###" ],
            [ 'fr', "#,##0.###" ],
            [ 'ja', "#,##0.###" ]

        ];
    }
}
