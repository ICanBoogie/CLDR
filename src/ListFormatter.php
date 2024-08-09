<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Locale\ListPattern;

/**
 * Formats variable-length lists of things such as "Monday, Tuesday, Friday, and Saturday".
 *
 * @see http://www.unicode.org/reports/tr35/tr35-general.html#ListPatterns
 *
 * @implements Localizable<ListFormatter, LocalizedListFormatter>
 */
class ListFormatter implements Formatter, Localizable
{
    /**
     * Formats variable-length lists of scalars.
     *
     * @param scalar[] $list
     */
    public function format(array $list, ListPattern $list_pattern): string
    {
        $list = array_values($list);

        switch (count($list)) {
            case 0:
                return "";

            case 1:
                return (string)current($list);

            case 2:
                return $this->format_pattern($list_pattern->two, (string)$list[0], (string)$list[1]);

            default:
                $n = count($list) - 1;
                $v1 = $list[$n];

                for ($i = $n - 1; $i > -1; $i--) {
                    $v0 = $list[$i];

                    if ($i === 0) {
                        $pattern = $list_pattern->start;
                    } else {
                        if ($i + 1 === $n) {
                            $pattern = $list_pattern->end;
                        } else {
                            $pattern = $list_pattern->middle;
                        }
                    }

                    $v1 = $this->format_pattern($pattern, (string)$v0, (string)$v1);
                }

                return $v1;
        }
    }

    private function format_pattern(string $pattern, string $v0, string $v1): string
    {
        return strtr($pattern, [

            '{0}' => $v0,
            '{1}' => $v1

        ]);
    }

    public function localized(Locale $locale): LocalizedListFormatter
    {
        return new LocalizedListFormatter($this, $locale);
    }
}
