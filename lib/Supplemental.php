<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Supplemental\CurrencyData;

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
 * @property-read CurrencyData $currency_data
 * @uses self::get_currency_data()
 */
final class Supplemental extends AbstractSectionCollection
{
	/**
	 * @uses get_currency_data
	 */
	use AccessorTrait;

	/**
	 * Where _key_ is a property, matching a CLDR filename, and _value_ is an array path under "supplemental".
	 */
	private const OFFSET_MAPPING = [

	    'aliases'                => 'metadata/alias',
		'calendarData'           => 'calendarData',
		'calendarPreferenceData' => 'calendarPreferenceData',
		'characterFallbacks'     => 'characters/character-fallback',
		'codeMappings'           => 'codeMappings',
		'currencyData'           => 'currencyData',
		'dayPeriods'             => 'dayPeriodRuleSet',
		'gender'                 => 'gender',
		'grammaticalFeatures'    => 'grammaticalData',
		'languageData'           => 'languageData',
		'languageGroups'         => 'languageGroups',
		'languageMatching'       => 'languageMatching',
		'likelySubtags'          => 'likelySubtags',
		'measurementData'        => 'measurementData',
		'metaZones'              => 'metaZones',
		'numberingSystems'       => 'numberingSystems',
		'ordinals'               => 'plurals-type-ordinal',
		'parentLocales'          => 'parentLocales/parentLocale',
		'pluralRanges'           => 'plurals',
		'plurals'                => 'plurals-type-cardinal',
		'primaryZones'           => 'primaryZones',
		'references'             => 'references',
		'territoryContainment'   => 'territoryContainment',
		'territoryInfo'          => 'territoryInfo',
		'timeData'               => 'timeData',
        'unitPreferenceData'     => 'unitPreferenceData',
		'weekData'               => 'weekData',
		'windowsZones'           => 'windowsZones',

	];

	private CurrencyData $currency_data;

	private function get_currency_data(): CurrencyData
	{
		/* @phpstan-ignore-next-line */
		return $this->currency_data ??= new CurrencyData($this['currencyData']);
	}

	public function offsetExists($offset): bool
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
}
