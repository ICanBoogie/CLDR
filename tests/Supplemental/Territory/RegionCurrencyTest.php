<?php

namespace Test\ICanBoogie\CLDR\Supplemental\Territory;

use ICanBoogie\CLDR\Supplemental\Territory\RegionCurrency;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RegionCurrencyTest extends TestCase
{
    /**
     * @param array<string, array{ _from?: string, _to?: string, _tender?: string }> $data
     */
    #[DataProvider("provide_from")]
    public function test_from(array $data): void
    {
        $code = key($data);
        $properties = current($data) + [
                '_tender' => null,
                '_from' => null,
                '_to' => null,
            ];

        $actual = RegionCurrency::from($data);

        $this->assertEquals($code, $actual->code);
        $this->assertEquals(!($properties['_tender'] === 'false'), $actual->tender);
        $this->assertEquals($properties['_from'], $actual->from);
        $this->assertEquals($properties['_to'], $actual->to);
    }

    public static function provide_from(): array
    {
        return [

            [ [ 'BEC' => [ '_tender' => 'false', '_from' => '1970-01-01', '_to' => '1990-03-05' ] ] ],
            [ [ 'BEF' => [ '_from' => '1831-02-07', '_to' => '2002-02-28' ] ] ],
            [ [ 'EUR' => [ '_from' => '1999-01-01' ] ] ],

        ];
    }
}
