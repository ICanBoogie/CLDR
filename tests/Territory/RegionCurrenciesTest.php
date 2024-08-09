<?php

namespace Test\ICanBoogie\CLDR\Territory;

use ICanBoogie\CLDR\Territory\RegionCurrencies;
use ICanBoogie\CLDR\Territory\RegionCurrency;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RegionCurrenciesTest extends TestCase
{
    private RegionCurrencies $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = RegionCurrencies::from([
            [ 'EUR' => [ '_from' => '1999-01-01' ] ],
            [ 'BEC' => [ '_tender' => 'false', '_from' => '1970-01-01', '_to' => '1990-03-05' ] ],
            [ 'BEF' => [ '_from' => '1831-02-07', '_to' => '2002-02-28' ] ],
        ]);
    }

    public function test_from(): void
    {
        $actual = iterator_to_array($this->sut);
        $expected = [
            RegionCurrency::from([ 'BEF' => [ '_from' => '1831-02-07', '_to' => '2002-02-28' ] ]),
            RegionCurrency::from([ 'BEC' => [ '_tender' => 'false', '_from' => '1970-01-01', '_to' => '1990-03-05' ] ]),
            RegionCurrency::from([ 'EUR' => [ '_from' => '1999-01-01' ] ]),
        ];

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider("provide_at")]
    public function test_at(string $date, string $expected): void
    {
        $actual = $this->sut->at($date);
        $this->assertEquals($expected, $actual?->code);
    }

    public static function provide_at(): array
    {
        return [
            [ '1900-01-01', 'BEF' ],
            // Both BEF and EUR are available, but BEF wins because it is older
            [ '1999-01-01', 'BEF' ],
            [ '2024-01-01', 'EUR' ],
        ];
    }
}
