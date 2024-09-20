<?php

namespace ICanBoogie\CLDR\Core;

/**
 * @link https://www.unicode.org/reports/tr35/tr35-75/tr35.html#Likely_Subtags
 */
final class LikelySubtags
{
    /**
     * @return array{ string, string, string }
     *     Where `0` is the likely language, `1` is the likely script, and `2` is the likely region.
     */
    public static function add(?string $language, ?string $script = null, ?string $region = null): array
    {
        $language ??= 'und';
        $language = strtolower($language);

        if ($script) {
            $script = mb_convert_case($script, MB_CASE_TITLE);
        }
        if ($region) {
            $region = strtoupper($region);
        }

        $value = implode('-', array_filter([ $language, $script, $region ]));

        if ($script === 'Zzzz') {
            $script = null;
        }

        if ($region === 'ZZ') {
            $region = null;
        }

        $tries = [];

        if ($script && $region) {
            $tries[] = "$language-$script-$region";
        }
        if ($script) {
            $tries[] = "$language-$script";
        }
        if ($region) {
            $tries[] = "$language-$region";
        }

        $tries[] = $language;

        foreach ($tries as $try) {
            if (isset(LocaleData::LIKELY_SUBTAGS[$try])) {
                [ $l, $s, $r ] = explode('-', LocaleData::LIKELY_SUBTAGS[$try]);

                return [ $language === 'und' ? $l : $language, $script ?? $s, $region ?? $r ];
            }
        }

        throw new InvalidLanguageId("Unable to resolve likely subtags from: $value");
    }
}
