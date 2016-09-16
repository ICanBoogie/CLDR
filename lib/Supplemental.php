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
 */
class Supplemental extends AbstractSectionCollection
{
	static private $available_sections = [

		'calendarData'           => 'calendarData',
		'calendarPreferenceData' => 'calendarPreferenceData',
		'characterFallbacks'     => 'characters/character-fallback',
		'codeMappings'           => 'codeMappings',
		'currencyData'           => 'currencyData',
		'dayPeriods'             => 'dayPeriodRuleSet',
		'gender'                 => 'gender',
		'languageData'           => 'languageData',
		'languageMatching'       => 'languageMatching',
		'likelySubtags'          => 'likelySubtags',
		'measurementData'        => 'measurementData',
		'metaZones'              => 'metaZones',
		'numberingSystems'       => 'numberingSystems',
		'ordinals'               => 'plurals-type-ordinal',
		'parentLocales'          => 'parentLocales',
		'plurals'                => 'plurals-type-cardinal',
		'postalCodeData'         => 'postalCodeData',
		'primaryZones'           => 'primaryZones',
		'references'             => 'references',
		'telephoneCodeData'      => 'telephoneCodeData',
		'territoryContainment'   => 'territoryContainment',
		'territoryInfo'          => 'territoryInfo',
		'timeData'               => 'timeData',
		'weekData'               => 'weekData',
		'windowsZones'           => 'windowsZones'

	];

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		parent::__construct($repository, 'supplemental', self::$available_sections);
	}
}
