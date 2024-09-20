<?php

namespace ICanBoogie\CLDR\Supplemental;

use Closure;
use ICanBoogie\CLDR\AbstractSectionCollection;
use ICanBoogie\CLDR\Warmable;

/**
 * Representation of the "supplemental" section.
 *
 * <pre>
 * <?php
 *
 * use ICanBoogie\CLDR\Supplemental;
 *
 * $supplemental = new Supplemental($repository);
 *
 * echo $supplemental['calendarPreferenceData']['001']; // gregorian
 * </pre>
 *
 * @extends AbstractSectionCollection<string>
 */
final class Supplemental extends AbstractSectionCollection implements Warmable
{
    /**
     * Where _key_ is a property, matching a CLDR filename, and _value_ is an array path under "supplemental".
     */
    private const OFFSET_MAPPING = [

        'aliases' => 'metadata/alias',
        'calendarData' => 'calendarData',
        'calendarPreferenceData' => 'calendarPreferenceData',
        'characterFallbacks' => 'characters/character-fallback',
        'codeMappings' => 'codeMappings',
        'currencyData' => 'currencyData',
        'dayPeriods' => 'dayPeriodRuleSet',
        'gender' => 'gender',
        'grammaticalFeatures' => 'grammaticalData',
        'languageData' => 'languageData',
        'languageGroups' => 'languageGroups',
        'languageMatching' => 'languageMatching',
        'likelySubtags' => 'likelySubtags',
        'measurementData' => 'measurementData',
        'metaZones' => 'metaZones',
        'numberingSystems' => 'numberingSystems',
        'ordinals' => 'plurals-type-ordinal',
        'parentLocales' => 'parentLocales/parentLocale',
        'pluralRanges' => 'plurals',
        'plurals' => 'plurals-type-cardinal',
        'primaryZones' => 'primaryZones',
        'references' => 'references',
        'territoryContainment' => 'territoryContainment',
        'territoryInfo' => 'territoryInfo',
        'timeData' => 'timeData',
        'unitPreferenceData' => 'unitPreferenceData',
        'weekData' => 'weekData',
        'windowsZones' => 'windowsZones',

    ];

    public function offsetExists(mixed $offset): bool
    {
        return isset(self::OFFSET_MAPPING[$offset]);
    }

    protected function path_for(string $offset): string
    {
        return "core/supplemental/$offset";
    }

    protected function data_path_for(string $offset): string
    {
        return "supplemental/" . self::OFFSET_MAPPING[$offset];
    }

    public function warm_up(Closure $progress): void
    {
        $progress("Warming up supplemental:");

        foreach (array_keys(self::OFFSET_MAPPING) as $offset) {
            $progress("- $offset");
            $this->offsetGet($offset);
        }
    }
}
