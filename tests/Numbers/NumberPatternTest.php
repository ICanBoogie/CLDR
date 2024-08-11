<?php

namespace Test\ICanBoogie\CLDR\Numbers;

use ICanBoogie\CLDR\Numbers\NumberPattern;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NumberPatternTest extends TestCase
{
    #[DataProvider('provide_test_properties')]
    public function test_properties(string $pattern, array $properties): void
    {
        $instance = NumberPattern::from($pattern);

        $this->assertSame($pattern, (string)$instance);

        foreach ($properties as $property => $value) {
            $this->assertSame($value, $instance->$property);
        }
    }

    public static function provide_test_properties(): array
    {
        $default = [

            'positive_prefix' => '',
            'positive_suffix' => '',
            'negative_prefix' => '',
            'negative_suffix' => '',
            'multiplier' => 1,
            'decimal_digits' => 0,
            'max_decimal_digits' => 0,
            'integer_digits' => 0,
            'group_size1' => 0,
            'group_size2' => 0

        ];

        return [

            [
                "#,##0.###",
                array_merge($default, [

                    'negative_prefix' => "-",
                    'max_decimal_digits' => 3,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ],

            [
                "#,##0.00 ¤;(#,##0.00 ¤)",
                array_merge($default, [

                    'positive_suffix' => " ¤",
                    'negative_prefix' => "(",
                    'negative_suffix' => " ¤)",
                    'decimal_digits' => 2,
                    'max_decimal_digits' => 2,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ],

            [
                "#,##0.##",
                array_merge($default, [

                    'negative_prefix' => "-",
                    'max_decimal_digits' => 2,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ],

            [
                "#,##0 %",
                array_merge($default, [

                    'positive_suffix' => ' %',
                    'negative_prefix' => '-',
                    'negative_suffix' => ' %',
                    'multiplier' => 100,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ],

            [
                "#,##0.### %",
                array_merge($default, [

                    'positive_suffix' => " %",
                    'negative_prefix' => "-",
                    'negative_suffix' => " %",
                    'multiplier' => 100,
                    'max_decimal_digits' => 3,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ],

            [
                "#,##0.### ‰",
                array_merge($default, [

                    'positive_suffix' => " ‰",
                    'negative_prefix' => "-",
                    'negative_suffix' => " ‰",
                    'multiplier' => 1000,
                    'max_decimal_digits' => 3,
                    'integer_digits' => 1,
                    'group_size1' => 3

                ])
            ]

        ];
    }

    /**
     * @dataProvider provide_test_format_integer_with_decimal
     */
    public function test_format_integer_with_decimal(
        string $pattern,
        int $integer,
        string $decimal,
        string $expected
    ): void {
        $sut = NumberPattern::from($pattern);
        $actual = $sut->format_integer_with_decimal($integer, $decimal, '/');
        $this->assertSame($expected, $actual);
    }

    public static function provide_test_format_integer_with_decimal(): array
    {
        return [

            [ "#,##0.###", 1, "0", '1' ],
            [ "#,##0.##0", 1, "3", '1/300' ],
            [ "#,##0.##0", 1, "345", '1/345' ],
            [ "#,##0.##0", 1, "34567", '1/34567' ],

        ];
    }
}
