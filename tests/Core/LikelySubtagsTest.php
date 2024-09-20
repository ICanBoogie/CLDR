<?php

namespace Test\ICanBoogie\CLDR\Core;

use ICanBoogie\CLDR\Core\LikelySubtags;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LikelySubtagsTest extends TestCase
{
    #[DataProvider('provide_add')]
    public function test_add(array $args, array $expected): void
    {
        $actual = LikelySubtags::add(...$args);

        $this->assertEquals($expected, $actual, "for " . implode('-', array_filter($args)));
    }

    public static function provide_add(): array
    {
        return [

            [ [ 'fr' ], [ 'fr', 'Latn', 'FR' ] ],
            [ [ 'fa', 'Arab', 'AF' ], [ 'fa', 'Arab', 'AF' ] ],
            [ [ 'und', 'Arab', 'AF' ], [ 'fa', 'Arab', 'AF' ] ],
            [ [ 'fa', null, 'AF' ], [ 'fa', 'Arab', 'AF' ] ],
            [ [ 'ZH', 'ZZZZ', 'SG' ], [ 'zh', 'Hans', 'SG' ] ],
            [ [ 'zh', null, 'TW' ], [ 'zh', 'Hant', 'TW' ] ],
            [ [ 'zh' ], [ 'zh', 'Hans', 'CN' ] ],

        ];
    }
}
