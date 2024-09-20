<?php

namespace Test\ICanBoogie\CLDR\Core;

use DateTimeImmutable;
use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\LocaleId;
use ICanBoogie\CLDR\Dates\Calendar;
use ICanBoogie\CLDR\Dates\DateTimeFormatLength;
use ICanBoogie\CLDR\General\Lists\ListFormatterLocalized;
use ICanBoogie\CLDR\General\Transforms\ContextTransforms;
use ICanBoogie\CLDR\Numbers\CurrencyFormatterLocalized;
use ICanBoogie\CLDR\Numbers\NumberFormatterLocalized;
use ICanBoogie\CLDR\Numbers\Numbers;
use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\Supplemental\Units\Units;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Test\ICanBoogie\CLDR\StringHelpers;

use function Test\ICanBoogie\CLDR\get_repository;
use function Test\ICanBoogie\CLDR\locale_for;

final class LocaleTest extends TestCase
{
    use StringHelpers;

    private static Locale $locale;

    public static function setupBeforeClass(): void
    {
        self::$locale = new Locale(get_repository(), LocaleId::from('fr'));
    }

    public function test_get_code(): void
    {
        $this->assertEquals(LocaleId::from('fr'), self::$locale->id);
    }

    public function test_get_language(): void
    {
        $locale = new Locale(get_repository(), LocaleId::parse('fr-BE'));

        $this->assertEquals('fr', $locale->language);
    }

    /**
     * @param class-string $expected
     */
    #[DataProvider('provide_test_properties_instanceof')]
    public function test_properties_instanceof(string $property, string $expected): void
    {
        $locale = new Locale(get_repository(), LocaleId::from('fr'));
        $instance = $locale->$property;
        $this->assertInstanceOf($expected, $instance);
        $this->assertSame($instance, $locale->$property);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_properties_instanceof(): array
    {
        return [

            [ 'repository', Repository::class ],
            [ 'calendar', Calendar::class ],
            [ 'numbers', Numbers::class ],
            [ 'number_formatter', NumberFormatterLocalized::class ],
            [ 'currency_formatter', CurrencyFormatterLocalized::class ],
            [ 'list_formatter', ListFormatterLocalized::class ],
            [ 'context_transforms', ContextTransforms::class ],
            [ 'units', Units::class ],

        ];
    }

    #[DataProvider('provide_test_sections')]
    public function test_sections(string $section, string $key): void
    {
        $section_data = self::$locale[$section];
        $this->assertIsArray($section_data);
        $this->assertArrayHasKey($key, $section_data);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_sections(): array
    {
        return [

            [ 'ca-buddhist', 'months' ],
            [ 'ca-chinese', 'months' ],
            [ 'ca-coptic', 'months' ],
            [ 'ca-dangi', 'months' ],
            [ 'ca-ethiopic', 'months' ],
            [ 'ca-hebrew', 'months' ],
            [ 'ca-indian', 'months' ],
            [ 'ca-islamic', 'months' ],
            [ 'ca-japanese', 'months' ],
            [ 'ca-persian', 'months' ],
            [ 'ca-roc', 'months' ],
            [ 'ca-generic', 'months' ],
            [ 'ca-gregorian', 'months' ],
            [ 'dateFields', 'era' ],
            [ 'timeZoneNames', 'hourFormat' ],
            [ 'languages', 'aa' ],
            [ 'localeDisplayNames', 'localeDisplayPattern' ],
            [ 'scripts', 'Arab' ],
            [ 'territories', 'AC' ],
            [ 'variants', 'AREVELA' ],
            [ 'characters', 'exemplarCharacters' ],
            [ 'contextTransforms', 'day-format-except-narrow' ],
            [ 'delimiters', 'quotationStart' ],
            [ 'layout', 'orientation' ],
            [ 'listPatterns', 'listPattern-type-standard' ],
            [ 'posix', 'messages' ],
            [ 'currencies', 'ADP' ],
            [ 'numbers', 'defaultNumberingSystem' ],
            [ 'measurementSystemNames', 'metric' ],
            [ 'units', 'long' ],

        ];
    }

    /**
     * @param class-string $expected
     */
    #[DataProvider('provide_localize')]
    public function test_localize(mixed $locale, string $expected): void
    {
        $actual = self::$locale->localized($locale);

        $this->assertEquals($expected, $actual->name);
    }

    public static function provide_localize(): array
    {
        return [

            [ 'fr', "français" ],
            [ LocaleId::from('fr'), "français" ],
            [ locale_for('fr'), "français" ],
            [ 'en', "French" ],
            [ LocaleId::from('en'), "French" ],
            [ locale_for('en'), "French" ],

        ];
    }

    public function test_format_number(): void
    {
        $this->assertStringSame(
            "123 456,78",
            self::$locale->format_number(123456.78),
        );
    }

    public function test_format_percent(): void
    {
        $this->assertStringSame(
            "12 %",
            self::$locale->format_percent(.1234),
        );
    }

    public function test_format_currency(): void
    {
        $this->assertStringSame(
            "123 456,78 €",
            self::$locale->format_currency(123456.78, 'EUR'),
        );
    }

    public function test_format_list(): void
    {
        $this->assertSame(
            "lundi, mardi et mercredi",
            self::$locale->format_list([ "lundi", "mardi", "mercredi" ]),
        );
    }

    #[DataProvider("provide_context_transforms_availability")]
    public function test_context_transforms_availability(string $locale_id, bool $expected): void
    {
        $actual = count(locale_for($locale_id)['contextTransforms']) > 0;

        $this->assertSame($expected, $actual);
    }

    // @phpstan-ignore-next-line
    public static function provide_context_transforms_availability(): array
    {
        return [
            [ 'en', true ],
            [ 'fr-BE', true ],
            [ 'ja', false ],
            [ 'zh', false ],
        ];
    }

    public function test_context_transform(): void
    {
        $this->assertEquals(
            "Juin",
            self::$locale->context_transform(
                "juin",
                ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
                ContextTransforms::TYPE_STAND_ALONE,
            ),
        );
    }

    public function test_warm_up(): void
    {
        $n = 0;

        self::$locale->warm_up(function () use (&$n) {
            $n++;
        });

        $this->assertEquals(31, $n);
    }

    public function test_override_calendar_by_region(): void
    {
        $datetime = new DateTimeImmutable('2025-06-06 12:35:45');
        $locale = locale_for('fr-AF');
        $actual = $locale->calendar->format_date($datetime, DateTimeFormatLength::LONG);

        $this->assertEquals('6 šahrivar 2025 A. P.', $actual);
    }

    public function test_override_calendar_by_extension(): void
    {
        $datetime = new DateTimeImmutable('2025-06-06 12:35:45');
        $locale = locale_for('fr-FR-u-rg-AF');
        $actual = $locale->calendar->format_date($datetime, DateTimeFormatLength::LONG);

        $this->assertEquals('6 šahrivar 2025 A. P.', $actual);
    }
}
