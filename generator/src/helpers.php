<?php

namespace ICanBoogie\CLDR\Generator;

/**
 * Indent a multi-line string.
 */
function indent(string $str, int $level = 0): string
{
    $parts = array_filter(explode("\n", $str));
    $indent = str_repeat(' ', $level * 4);

    $parts = array_map(
        fn($part) =>  $indent . $part,
        $parts
    );

    return implode("\n", $parts);
}
