<?php

namespace ICanBoogie\CLDR;

use function preg_replace;

final class UTF8Helpers
{
    public static function trim(string $string): string
    {
        /** @var string */
        return preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $string);
    }
}
