<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Locale\ListPattern;

/**
 * Formats variable-length lists of things such as "Monday, Tuesday, Friday, and Saturday".
 *
 * @link https://www.unicode.org/reports/tr35/tr35-general.html#ListPatterns
 *
 * @implements Localizable<ListFormatter, LocalizedListFormatter>
 */
final class ListFormatter implements Formatter, Localizable
{
    /**
     * Formats variable-length lists of scalars.
     *
     * @param scalar[] $list
     */
    public function format(array $list, ListPattern $list_pattern): string
    {
        $list = array_values($list);

        return match (count($list)) {
            0 => "",
            1 => (string)current($list),
            2 => $this->format_two($list, $list_pattern),
            default => $this->format_many($list, $list_pattern),
        };
    }

    /**
     * @param scalar[] $list
     */
    private function format_two(array $list, ListPattern $list_pattern): string
    {
        return $this->format_pattern($list_pattern->two, (string)$list[0], (string)$list[1]);
    }

    /**
     * @param scalar[] $list
     */
    private function format_many(array $list, ListPattern $list_pattern): string
    {
        $n = count($list) - 1;
        $v1 = (string)$list[$n];

        for ($i = $n - 1; $i > -1; $i--) {
            $v0 = $list[$i];

            $pattern = match ($i) {
                0 => $list_pattern->start,
                $n - 1 => $list_pattern->end,
                default => $list_pattern->middle,
            };

            $v1 = $this->format_pattern($pattern, (string)$v0, (string)$v1);
        }

        return $v1;
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
