<?php

namespace Core;

use ICanBoogie\CLDR\Core\LanguageId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LanguageIdTest extends TestCase
{
    #[DataProvider('provide_language_id')]
    public function test_parse(string $language_id, string $_, LanguageId $expected): void
    {
        $actual = LanguageId::parse($language_id);

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('provide_language_id')]
    public function test_to_string(string $_, string $expected, LanguageId $language_id): void
    {
        $actual = (string)$language_id;

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<array{ string, string, LanguageId }>
     */
    public static function provide_language_id(): array
    {
        return [

            [
                'und-Hans',
                'zh-Hans-CN',
                new LanguageId('zh', 'Hans', 'CN'),
            ],

            [
                'zh-Hans',
                'zh-Hans-CN',
                new LanguageId('zh', 'Hans', 'CN'),
            ],

            [
                'ZH-ZZZZ-SG',
                'zh-Hans-SG',
                new LanguageId('zh', 'Hans', 'SG'),
            ],

            [
                'zh-TW',
                'zh-Hant-TW',
                new LanguageId('zh', 'Hant', 'TW'),
            ],

            [
                'zh',
                'zh-Hans-CN',
                new LanguageId('zh', 'Hans', 'CN'),
            ],

            [
                'pt-BR',
                'pt-Latn-BR',
                new LanguageId('pt', 'Latn', 'BR'),
            ],

            [
                'es-419',
                'es-Latn-419',
                new LanguageId('es', 'Latn', '419'),
            ],

            [
                'hy-Latn-IT',
                'hy-Latn-IT',
                new LanguageId('hy', 'Latn', 'IT'),
            ],

            [
                'sl-rozaj',
                'sl-Latn-SI-rozaj',
                new LanguageId('sl', 'Latn', 'SI', [ 'rozaj' ]),
            ],

            [
                'fr',
                'fr-Latn-FR',
                new LanguageId('fr', 'Latn', 'FR'),
            ],

            [
                'fr-FR-1694acad',
                'fr-Latn-FR-1694acad',
                new LanguageId('fr', 'Latn', 'FR', [ '1694acad' ]),
            ],

            [
                'de-Latf-fonipa',
                'de-Latf-DE-fonipa',
                new LanguageId('de', 'Latf', 'DE', variants: [ 'fonipa' ]),
            ],

            // variants are ordered
            [
                'de-Latn-DE-1996-fonipa-1901',
                'de-Latn-DE-1901-1996-fonipa',
                new LanguageId('de', 'Latn', 'DE', variants: [ '1901', '1996', 'fonipa' ]),
            ],

            [
                'ga-Latg-IE-ulster',
                'ga-Latg-IE-ulster',
                new LanguageId('ga', 'Latg', 'IE', variants: [ 'ulster' ]),
            ],

            [
                'fa-Arab-AF',
                'fa-Arab-AF',
                new LanguageId('fa', 'Arab', 'AF'),
            ],

            [
                'und-Arab-AF',
                'fa-Arab-AF',
                new LanguageId('fa', 'Arab', 'AF'),
            ],

            [
                'fa-AF',
                'fa-Arab-AF',
                new LanguageId('fa', 'Arab', 'AF'),
            ],

        ];
    }

    /**
     * @param string[] $variants
     */
    #[DataProvider('provide_of')]
    public function test_of(
        ?string $language,
        ?string $script,
        ?string $region,
        array $variants,
        LanguageId $expected,
    ): void {
        $actual = LanguageId::of($language, $script, $region, $variants);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array< array{ ?string, ?string, ?string, string[], LanguageId }>
     */
    public static function provide_of(): array
    {
        return [

            [
                'fr',
                null,
                null,
                [],
                new LanguageId('fr', 'Latn', 'FR'),
            ],

            [
                'fr',
                null,
                'ES',
                [],
                new LanguageId('fr', 'Latn', 'ES'),
            ],

            [
                'fr',
                'Hans',
                'ES',
                [],
                new LanguageId('fr', 'Hans', 'ES'),
            ],

            // variants are ordered
            [
                'de',
                null,
                null,
                [ '1996', 'fonipa', '1901' ],
                new LanguageId('de', 'Latn', 'DE', [ '1901', '1996', 'fonipa' ]),
            ],

        ];
    }
}
