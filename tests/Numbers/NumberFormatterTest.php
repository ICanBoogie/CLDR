<?php

namespace Test\ICanBoogie\CLDR\Numbers;

use ICanBoogie\CLDR\Numbers\NumberFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\locale_for;

final class NumberFormatterTest extends TestCase
{
    #[DataProvider('provide_test_format')]
    public function test_format(string $locale_id, float|int $number, string $pattern, string $expected): void
    {
        $formatter = new NumberFormatter();
        $symbols = locale_for($locale_id)->numbers->symbols;
        $this->assertSame($expected, $formatter->format($number, $pattern, $symbols));
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_format(): array
    {
        return [

            [ 'en', 123, '#', "123" ],
            [ 'en', -123, '#', "-123" ],
            [ 'en', 123, '#;-#', "123" ],
            [ 'en', -123, '#;-#', "-123" ],
            [ 'en', 4123.37, '#,#00.#0', "4,123.37" ],
            [ 'fr', 4123.37, '#,#00.#0', "4 123,37" ],
            [ 'fr', -4123.37, '#,#00.#0', "-4 123,37" ],
            [ 'en', .3789, '#0.#0 %', "37.89 %" ],
            [ 'fr', .3789, '#0.#0 %', "37,89 %" ],

        ];
    }
}
