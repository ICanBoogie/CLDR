<?php

namespace Test\ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Core\LanguageId;
use ICanBoogie\CLDR\Core\LocaleId;
use ICanBoogie\CLDR\Core\LocaleNotAvailable;
use ICanBoogie\CLDR\Core\TransformLocaleExtensions;
use ICanBoogie\CLDR\Core\UnicodeLocaleExtensions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocaleIdTest extends TestCase
{
    #[DataProvider('provide_test_is_locale_available')]
    public function test_is_locale_available(string $locale_id, bool $expected): void
    {
        $this->assertSame($expected, LocaleId::is_available($locale_id));
    }

    /** @phpstan-ignore-next-line */
    public static function provide_test_is_locale_available(): array
    {
        return [

            [ 'fr', true ],
            [ 'en', true ],
            [ 'en-AG', true ],
            [ 'fr-FR', false ],
            [ 'en-US', false ],

        ];
    }

    public function test_assert_is_available(): void
    {
        $this->expectException(LocaleNotAvailable::class);
        LocaleId::assert_is_available('fr-US');
    }

    #[DataProvider('provide_parse')]
    public function test_parse(
        string $locale_id,
        string $expected_usage,
        LanguageId $expected_language_id,
        UnicodeLocaleExtensions $expected_unicode_ext = new UnicodeLocaleExtensions(),
        TransformLocaleExtensions $expected_transform_ext = new TransformLocaleExtensions(),
    ): void {
        $actual = LocaleId::parse($locale_id);

        $this->assertEquals($locale_id, $actual->value);
        $this->assertEquals($expected_usage, $actual->available_id);
        $this->assertEquals($expected_language_id, $actual->language_id);
        $this->assertEquals($expected_unicode_ext, $actual->unicode_ext);
        $this->assertEquals($expected_transform_ext, $actual->transform_ext);
    }

    /**
     * @return array<array{ string, string, LanguageId, 3?: UnicodeLocaleExtensions, 4?: TransformLocaleExtensions }>
     */
    public static function provide_parse(): array
    {
        return [

            [
                'fr-Latn-FR',
                'fr',
                new LanguageId('fr', 'Latn', 'FR'),
            ],

            [
                'fr-FR-u-ca-gregory-t-k0-azerty-x-foo',
                'fr',
                new LanguageId('fr', 'Latn', 'FR'),
                new UnicodeLocaleExtensions(
                    calendar: 'gregory',
                ),
                new TransformLocaleExtensions(
                    keyboard_transform: 'azerty'
                )
                // TODO: private
            ],

        ];
    }

    public static function provide_locale(): array
    {
        return [
            [ 'fr-FR-u-ca-islamic-umalqura-cf-account-co-search-cu-EUR-dx-hani-hira-kata-em-emoji-fw-mon-hc-h11-lb-loose-lw-breakall-ms-ussystem-mu-kelvin-nu-arabext-rg-uszzzz-sd-gbsct-ss-none-tz-UTC-va-posix-t-d0-ascii-s0-morse-m0-prprname-h0-hybrid-i0-handwrit-k0-android-t0-und' ],
            [ 'fr-FR-u-ca-gregory' ],
            [ 'fr-FR-t-ko-azerty' ],
            [ 'fr-FR-x-foo' ],
            [ 'fr-FR-u-ca-gregory-t-ko-azerty-x-foo' ],
            [ 'fr' ],
            [ 'fr-Latn' ],
            [ 'fr-FR' ],
            [ 'fr-Latn-FR' ],
            [ 'fr-FR-t-d0-ascii-s0-morse-m0-prprname-h0-hybrid-i0-handwrit-k0-android-t0-und' ],

        ];
    }

    #[DataProvider('provide_usage')]
    public function test_available_id(string $value, string $expected): void
    {
        $sut = LocaleId::from($value);
        $actual = $sut->available_id;

        $this->assertSame($expected, $actual);
    }

    /** @phpstan-ignore-next-line */
    public static function provide_usage(): array
    {
        return [

            [ 'fr', 'fr' ],
            [ 'fr-FR', 'fr' ],
            [ 'fr-Latn-FR', 'fr' ],
            [ 'fr-BE', 'fr-BE' ],
            [ 'en', 'en' ],
            [ 'en-AG', 'en-AG' ],
            [ 'az-Arab-IQ', 'az-Arab-IQ' ],
            [ 'be-tarask', 'be-tarask' ],
            [ 'ca-ES-valencia', 'ca-ES-valencia' ],
            [ 'ca-Latn-ES-valencia', 'ca-ES-valencia' ],

        ];
    }

    public function test_serialize(): void
    {
        $sut = LocaleId::from('en-AG');
        $actual = unserialize(serialize($sut));

        $this->assertEquals($sut, $actual);
    }

    #[DataProvider('provide_from')]
    public function test_from(mixed $value, LocaleId $expected): void
    {
        $actual = LocaleId::from($value);

        $this->assertEquals($expected, $actual);
    }

    public static function provide_from(): array
    {
        return [

            [ 'fr-FR', LocaleId::parse('fr-FR') ],
            [ LocaleId::parse('fr-FR'), LocaleId::parse('fr-FR') ],
            [ LanguageId::parse('fr-FR'), LocaleId::parse('fr-Latn-FR') ],

        ];
    }
}
