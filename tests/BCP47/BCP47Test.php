<?php

namespace Test\ICanBoogie\CLDR\BCP47;

use ICanBoogie\CLDR\BCP47\BCP47;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\get_repository;

final class BCP47Test extends TestCase
{
    private BCP47 $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new BCP47(get_repository());
    }

    #[DataProvider("provide_offset")]
    public function test_offset(string $offset, string $expected): void
    {
        $actual = implode(' ', array_keys($this->sut[$offset]));

        $this->assertEquals($expected, $actual);
    }

    public static function provide_offset(): array
    {
        return [
            [ 'calendar', 'ca fw hc'],
            [ 'collation', 'co ka kb kc kf kh kk kn kr ks kv vt'],
            [ 'currency', 'cf cu' ],
            [ 'measure', 'ms mu' ],
            [ 'number', 'nu' ],
            [ 'segmentation', 'dx lb lw ss' ],
            [ 'timezone', 'tz' ],
            [ 'transform-destination', 'd0 s0' ],
            [ 'transform', 'm0' ],
            [ 'transform_hybrid', 'h0' ],
            [ 'transform_ime', 'i0' ],
            [ 'transform_keyboard', 'k0' ],
            [ 'transform_mt', 't0' ],
            [ 'transform_private_use', 'x0' ],
            [ 'variant', 'em rg sd va' ],
        ];
    }
}
