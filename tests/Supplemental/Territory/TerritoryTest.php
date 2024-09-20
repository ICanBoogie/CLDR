<?php

namespace Test\ICanBoogie\CLDR\Supplemental\Territory;

use ICanBoogie\CLDR\Core\LocaleId;
use ICanBoogie\CLDR\Supplemental\Territory\Territory;
use ICanBoogie\CLDR\Supplemental\Territory\TerritoryCode;
use ICanBoogie\CLDR\Supplemental\Territory\TerritoryLocalized;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\get_repository;

final class TerritoryTest extends TestCase
{
    public function test_get_info(): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of('FR'));
        $this->assertIsArray($territory->info);
    }

    public function test_get_containment(): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of('EU'));
        $this->assertIsArray($territory->containment);
    }

    public function test_is_containing(): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of('EU'));

        $this->assertTrue($territory->is_containing('FR'));
        $this->assertFalse($territory->is_containing('TA'));
    }

    #[DataProvider('provide_test_get_currency')]
    public function test_get_currency(string $expected, string $territory_code): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $actual = $territory->currency;

        $this->assertEquals($expected, $actual?->code);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_currency(): array
    {
        return [

            [ 'EUR', 'FR' ],
            [ 'MMK', 'MM' ],
            [ 'USD', 'US' ]

        ];
    }

    #[DataProvider('provide_test_currency_at')]
    public function test_currency_at(string $expected, string $territory_code, mixed $date): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertEquals($expected, $territory->currency_at($date)?->code);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_currency_at(): array
    {
        return [

            [ 'EUR', 'FR', null ],
            [ 'EUR', 'FR', 'now' ],
            [ 'EUR', 'FR', new \DateTime() ],
            [ 'FRF', 'FR', '1960-01-01' ],
            [ 'FRF', 'FR', '1977-06-06' ],
            [ 'FRF', 'FR', new \DateTime('1977-06-06') ],
            [ 'USD', 'US', '1792-01-01' ]

        ];
    }

    #[DataProvider('provide_test_get_language')]
    public function test_get_language(string $expected, string $territory_code): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertSame($expected, $territory->language);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_language(): array
    {
        return [

            [ 'fr', 'FR' ],
            [ 'en', 'US' ],
            [ 'es', 'ES' ]

        ];
    }

    public function test_get_population(): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of('ES'));
        $this->assertNotEmpty($territory->population);
    }

    #[DataProvider('provide_test_name_as')]
    public function test_name_as(string $expected, string $territory_code, string $locale_id): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertEquals($expected, $territory->name_as(LocaleId::from($locale_id)));
        $this->assertEquals($expected, $territory->name_as($locale_id));
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_name_as(): array
    {
        return [

            [ "France", "FR", "fr" ],
            [ "France", "FR", "fr-BE" ],
            [ "Frankreich", "FR", "de" ],
            [ "フランス", "FR", "ja" ]

        ];
    }

    #[DataProvider('provide_test_get_name_as')]
    public function test_get_name_as(string $expected, string $territory_code, string $locale_id): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertEquals($expected, $territory->{'name_as_' . $locale_id});
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_name_as(): array
    {
        return [

            [ "France", "FR", "fr" ],
            [ "France", "FR", "fr_BE" ],
            [ "Frankreich", "FR", "de" ],
            [ "フランス", "FR", "ja" ]

        ];
    }

    #[DataProvider('provide_test_get_property')]
    public function test_get_property(string $expected, string $territory_code, string $property): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertEquals($expected, $territory->$property);
    }

    /**
     * @link https://github.com/unicode-org/cldr-json/blob/47.0.0/cldr-json/cldr-core/supplemental/weekData.json
     *
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_property(): array
    {
        return [

            # first_day

            [ "mon", "FR", 'first_day' ],
            [ "sat", "EG", 'first_day' ],
            [ "sun", "BS", 'first_day' ],
            [ "fri", "MV", 'first_day' ],

            # weekend_start

            [ "sat", "FR", 'weekend_start' ],
            [ "sat", "AE", 'weekend_start' ],
            [ "thu", "AF", 'weekend_start' ],
            [ "sun", "IN", 'weekend_start' ],

            # weekend_end

            [ "sun", "FR", 'weekend_end' ],
            [ "sun", "AE", 'weekend_end' ],
            [ "fri", "AF", 'weekend_end' ]

        ];
    }

    public function test_to_string(): void
    {
        $territory_code = 'US';
        $territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
        $this->assertEquals($territory_code, (string)$territory);
    }

    public function test_localize(): void
    {
        $territory = new Territory(get_repository(), TerritoryCode::of('FR'));
        $actual = $territory->localized(LocaleId::from('fr'));

        $this->assertInstanceOf(TerritoryLocalized::class, $actual);
    }
}
