<?php

namespace ICanBoogie\CLDR\BCP47;

use ICanBoogie\CLDR\AbstractSectionCollection;

/**
 * @extends AbstractSectionCollection<string>
 *
 * @link https://github.com/unicode-org/cldr-json/tree/47.0.0/cldr-json/cldr-bcp47/bcp47
 */
final class BCP47 extends AbstractSectionCollection
{
    private const OFFSET_MAPPING = [
        'calendar' => 'keyword/u',
        'collation' => 'keyword/u',
        'currency' => 'keyword/u',
        'measure' => 'keyword/u',
        'number' => 'keyword/u',
        'segmentation' => 'keyword/u',
        'timezone' => 'keyword/u',
        'transform-destination' => 'keyword/t',
        'transform' => 'keyword/t',
        'transform_hybrid' => 'keyword/t',
        'transform_ime' => 'keyword/t',
        'transform_keyboard' => 'keyword/t',
        'transform_mt' => 'keyword/t',
        'transform_private_use' => 'keyword/t',
        'variant' => 'keyword/u',
    ];

    public function offsetExists(mixed $offset): bool
    {
        return isset(self::OFFSET_MAPPING[$offset]);
    }

    protected function path_for(string $offset): string
    {
        return "bcp47/$offset";
    }

    protected function data_path_for(string $offset): string
    {
        return self::OFFSET_MAPPING[$offset];
    }
}
