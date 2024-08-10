<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\DateFormatPattern;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DateTimePatternTest extends TestCase
{
    #[DataProvider("provide_tokenize")]
    public function test_tokenize(string $pattern, array $expected): void
    {
        $actual = DateFormatPattern::tokenize($pattern);

        $this->assertEquals($expected, $actual);
    }

    public static function provide_tokenize(): array
    {
        return [

            [ 'G', [ [ 'G', 1 ] ] ],
            [ 'GG', [ [ 'G', 2 ] ] ],
            [ 'GGG', [ [ 'G', 3 ] ] ],
            [ 'GGGG', [ [ 'G', 4 ] ] ],
            [ 'GGGGG', [ [ 'G', 5 ] ] ],
            [ 'E d', [ [ 'E', 1 ], ' ', [ 'd', 1 ] ] ],
            [ 'E h:mm a', [ [ 'E', 1 ], ' ', [ 'h', 1 ], ':', [ 'm', 2 ], ' ', [ 'a', 1] ] ],
            [ 'E d/M/y', [ [ 'E', 1 ], ' ', [ 'd', 1 ], '/', [ 'M', 1 ], '/', [ 'y', 1] ] ],
            [ "E 'd/M/'y", [ [ 'E', 1 ], " d/M/", [ 'y', 1] ] ],
            [ "'week' W 'of' MMMM", [ "week ", [ 'W', 1 ], " of ", [ 'M', 4 ] ] ],
            [ "EEE, MMM d, ''yy", [ [ 'E', 3 ], ", ", [ 'M', 3 ], " ", [ 'd', 1 ], ", '", [ 'y', 2 ] ] ],
            [ "h:mm a", [ [ 'h', 1 ], ":", [ 'm', 2 ], " ", [ 'a', 1 ] ] ],
            [ "hh 'o''clock' a, zzzz", [ [ 'h', 2 ], " o'clock ", [ 'a', 1 ], ', ', [ 'z', 4 ] ] ],

        ];
    }
}
