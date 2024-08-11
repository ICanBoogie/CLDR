<?php

namespace Test\ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Core\Locale;
use ICanBoogie\CLDR\Core\LocaleId;
use ICanBoogie\CLDR\Core\LocaleLocalized;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\get_repository;
use function Test\ICanBoogie\CLDR\locale_for;

final class LocalizedLocaleTest extends TestCase
{
    #[DataProvider('provide_test_get_name')]
    public function test_get_name(string $locale_id, string $code, string $expected): void
    {
        $locale = new Locale(get_repository(), LocaleId::of($code));
        $localized = new LocaleLocalized($locale, locale_for($locale_id));

        $this->assertEquals($expected, $localized->name);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public static function provide_test_get_name(): array
    {
        return [

            [ 'fr', 'fr', "français" ],
            [ 'fr', 'fr-CA', "français canadien" ],
            [ 'en', 'fr', "French" ],
            [ 'en', 'fr-CA', "Canadian French" ],
            [ 'fr', 'nl', "néerlandais" ],
            [ 'fr', 'nl-BE', "flamand" ],

        ];
    }

    public function test_localize(): void
    {
        $locale = new Locale(get_repository(), LocaleId::of('fr'));
        $localized = $locale->localized(LocaleId::of('ja'));
        $this->assertInstanceOf(LocaleLocalized::class, $localized);
        $this->assertEquals("フランス語", $localized->name);
    }
}
