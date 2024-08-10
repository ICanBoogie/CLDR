<?php

namespace ICanBoogie\CLDR;

/**
 * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-dates.html#Date_Format_Patterns
 */
final class DateFormatPattern
{
    private const QUOTE = "'";

    /**
     * @param string $pattern
     *     A date format pattern; for example, "yyyy.MM.dd G 'at' HH:mm:ss zzz".
     *
     * @return array<string|array{ string, int }>
     *     Where _value_ is either a literal or an array where `0` is a pattern character and `1` its length.
     */
    public static function tokenize(string $pattern): array
    {
        static $cache = [];

        return $cache[$pattern] ??= self::do_tokenize($pattern);
    }

    /**
     * @param string $pattern
     *     A date format pattern; for example, "yyyy.MM.dd G 'at' HH:mm:ss zzz".
     *
     * @return array<string|array{ string, int }>
     *     Where _value_ is either a literal or an array where `0` is a pattern character and `1` its length.
     */
    private static function do_tokenize(string $pattern): array
    {
        $tokens = [];
        $is_literal = false;
        $literal = '';
        $z = mb_strlen($pattern);

        for ($i = 0; $i < $z; ++$i) {
            $c = mb_substr($pattern, $i, 1);

            if ($c === self::QUOTE) {
                // Two adjacent single vertical quotes (''), which represent a literal single quote,
                // either inside or outside a quoted text.
                if (mb_substr($pattern, $i + 1, 1) === self::QUOTE) {
                    $i++;
                    $literal .= self::QUOTE;
                } else {
                    // Toggle literal
                    $is_literal = !$is_literal;
                }
            } elseif ($is_literal) {
                $literal .= $c;
            } elseif (ctype_alpha($c)) {
                if ($literal) {
                    $tokens[] = $literal;
                    $literal = '';
                }

                for ($j = $i + 1; $j < $z; ++$j) {
                    $nc = mb_substr($pattern, $j, 1);
                    if ($nc !== $c) {
                        break;
                    }
                }
                $tokens[] = [ $c, $j - $i ];
                $i = $j - 1; // because +1 from the for loop
            } else {
                $literal .= $c;
            }
        }

        // If the pattern ends with literal (could also be a malformed quote)
        if ($literal) {
            $tokens[] = $literal;
        }

        return $tokens;
    }
}
