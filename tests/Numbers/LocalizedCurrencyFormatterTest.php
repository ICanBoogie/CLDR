<?php

namespace Test\ICanBoogie\CLDR\Numbers;

use ICanBoogie\CLDR\Numbers\CurrencyFormatter;
use ICanBoogie\CLDR\Numbers\CurrencyFormatterLocalized;
use ICanBoogie\CLDR\Numbers\NumberFormatter;
use ICanBoogie\CLDR\Spaces;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\ICanBoogie\CLDR\StringHelpers;

use function Test\ICanBoogie\CLDR\locale_for;

final class LocalizedCurrencyFormatterTest extends TestCase
{
    use StringHelpers;

    private CurrencyFormatter $sut;

    protected function setUp(): void
    {
        $this->sut = new CurrencyFormatter(new NumberFormatter());
    }

    #[DataProvider('provide_test_format')]
    public function test_format(
        string $currency_code,
        string $locale_id,
        float $number,
        string $expected
    ): void {
        $formatter = new CurrencyFormatterLocalized(
            $this->sut,
            locale_for($locale_id),
        );

        $this->assertStringSame($expected, $formatter->format($number, $currency_code));
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_format(): array
    {
        $s1 = Spaces::NARROW_NO_BREAK_SPACE;
        $s2 = Spaces::NO_BREAK_SPACE;

        return [

            [ 'IEP', 'fr', 123456.789, "123{$s1}456,79{$s2}£IE" ],
            [ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
            [ 'EUR', 'fr', 123456.789, "123{$s1}456,79{$s2}€" ],
            [ 'EUR', 'en', 123456.789, "€123,456.79" ],
            [ 'USD', 'fr', 123456.789, "123{$s1}456,79{$s2}\$US" ],
            [ 'USD', 'en', 123456.789, "\$123,456.79" ],

        ];
    }

    /**
     * @param float|int|numeric-string $number
     */
    #[DataProvider('provide_test_format_accounting')]
    public function test_format_accounting(
        string $currency_code,
        string $locale_id,
        float|int|string $number,
        string $expected
    ): void {
        $formatter = new CurrencyFormatterLocalized(
            $this->sut,
            locale_for($locale_id),
        );

        $this->assertStringSame(
            $expected,
            $formatter->format($number, $currency_code, $formatter::PATTERN_ACCOUNTING)
        );
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_format_accounting(): array
    {
        $s1 = Spaces::NARROW_NO_BREAK_SPACE;
        $s2 = Spaces::NO_BREAK_SPACE;

        return [

            [ 'IEP', 'fr', 123456.789, "123{$s1}456,79{$s2}£IE" ],
            [ 'IEP', 'en', 123456.789, "IEP123,456.79" ],
            [ 'EUR', 'fr', 123456.789, "123{$s1}456,79{$s2}€" ],
            [ 'EUR', 'en', 123456.789, "€123,456.79" ],
            [ 'USD', 'fr', 123456.789, "123{$s1}456,79{$s2}\$US" ],
            [ 'USD', 'en', 123456.789, "\$123,456.79" ],

        ];
    }

    public function test_should_format_with_custom_pattern(): void
    {
        $sut = new CurrencyFormatterLocalized(
            $this->sut,
            locale_for('fr'),
        );

        $actual = $sut->format(123.45, 'EUR', '¤0.0');

        $this->assertStringSame("€123,5", $actual);
    }
}
