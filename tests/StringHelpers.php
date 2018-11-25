<?php

namespace ICanBoogie\CLDR;

use function bin2hex;
use const PHP_EOL;
use function str_split;

trait StringHelpers
{
    protected function assertStringSame($expected, $actual)
    {
        $this->assertSame(
            $expected,
            $actual,
            implode(' ', str_split(bin2hex($expected), 2)) . PHP_EOL .
            implode(' ', str_split(bin2hex($actual), 2))
        );
    }
}
