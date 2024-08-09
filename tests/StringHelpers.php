<?php

namespace Test\ICanBoogie\CLDR;

use function bin2hex;
use function str_split;

use const PHP_EOL;

trait StringHelpers
{
    protected function assertStringSame(string $expected, string $actual): void
    {
        $this->assertSame(
            $expected,
            $actual,
            implode(' ', str_split(bin2hex($expected), 2)) . PHP_EOL .
            implode(' ', str_split(bin2hex($actual), 2))
        );
    }
}
