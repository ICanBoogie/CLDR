<?php

namespace Core;

use ICanBoogie\CLDR\Core\TransformLocaleExtensions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TransformLocaleExtensionsTest extends TestCase
{
    #[DataProvider('provide_parse')]
    public function test_parse(string $transform_ext, TransformLocaleExtensions $expected): void
    {
        $actual = TransformLocaleExtensions::parse($transform_ext);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array<array{ string, TransformLocaleExtensions }>
     */
    public static function provide_parse(): array
    {
        return [

            [
                't-m0-alaloc',
                new TransformLocaleExtensions(
                    transform_extension_mechanism: 'alaloc',
                ),
            ],

            [
                'm0-prprname-d0-morse-s0-ascii-i0-handwrit-k0-768dpi-t0-und-h0-hybrid-x0-madonna',
                new TransformLocaleExtensions(
                    transform_extension_mechanism: 'prprname',
                    transform_source: 'ascii',
                    transform_destination: 'morse',
                    input_method_engine_transform: 'handwrit',
                    keyboard_transform: '768dpi',
                    machine_translation: 'und',
                    hybrid_locale_identifiers: 'hybrid',
                    private_use: 'madonna',
                ),
            ],

        ];
    }
}
