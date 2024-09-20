<?php

namespace Test\ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Core\UnicodeLocaleExtensions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class UnicodeLocaleExtensionsTest extends TestCase
{
    #[DataProvider('provide_parse')]
    public function test_parse(string $unicode_ext, UnicodeLocaleExtensions $expected): void
    {
        $actual = UnicodeLocaleExtensions::parse($unicode_ext);

        $this->assertEquals($expected, $actual);
    }

    public static function provide_parse(): array
    {
        return [

            [
                'u-ca-buddhist',
                new UnicodeLocaleExtensions(
                    calendar: 'buddhist',
                ),
            ],

            [
                'ca-islamic-umalqura',
                new UnicodeLocaleExtensions(
                    calendar: 'islamic-umalqura',
                ),
            ],

            [
                'nu-fullwide',
                new UnicodeLocaleExtensions(
                    numbering_system: 'fullwide',
                ),
            ],

            [
                'co-phonebk',
                new UnicodeLocaleExtensions(
                    collation: 'phonebk',
                ),
            ],

            [
                'fw-tue',
                new UnicodeLocaleExtensions(
                    first_day_of_week: 'tue',
                ),
            ],

            [
                'ca-islamic-umalqura-cf-account-co-search-cu-eur-em-emoji-fw-mon-hc-h11-lb-loose-lw-breakall-ms-ussystem-mu-kelvin-nu-arabext-ss-none-tz-utc-va-posix',
                new UnicodeLocaleExtensions(
                    calendar: 'islamic-umalqura',
                    currency_format: 'account',
                    collation: 'search',
                    currency: 'eur',
                    emoji: 'emoji',
                    first_day_of_week: 'mon',
                    hour_cycle: 'h11',
                    line_break_style: 'loose',
                    line_break_word_handling: 'breakall',
                    measurement_system: 'ussystem',
                    measurement_unit_override: 'kelvin',
                    numbering_system: 'arabext',
                    sentence_break_suppressions: 'none',
                    time_zone: 'utc',
                    common_variant: 'posix',
                ),
            ],

//            [
//                'dx-hani-hira-kata',
//                new UnicodeLocaleExtensions(
//                    dictionary_break_script_exclusions:  [ 'Hani', 'Hira', 'Kata' ],
//                ),
//            ],

            [
                'rg-uszzzz',
                new UnicodeLocaleExtensions(
                    region_override: 'US',
                ),
            ],

//            [
//                'rg-uszzzz-sd-gbsct',
//                new UnicodeLocaleExtensions(
//                    region_override: 'uszzzz',
//                    regional_subdivision: 'gbsct',
//                ),
//            ],

        ];
    }
}
