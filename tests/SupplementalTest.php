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

class SupplementalTest extends \PHPUnit_Framework_TestCase
{
	static private $supplemental;

	static public function setupBeforeClass()
	{
		self::$supplemental = get_repository()->supplemental;
	}

	/**
	 * @dataProvider provide_test_sections
	 */
	public function test_sections($section, $key)
	{
		$section_data = self::$supplemental[$section];
		$this->assertInternalType('array', $section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	public function test_default_calendar()
	{
		$this->assertArrayHasKey('001', self::$supplemental['calendarPreferenceData']);
	}

	public function provide_test_sections()
	{
		return array
		(
			array('calendarData'           , 'buddhist'),
			array('calendarPreferenceData' , 'AE'),
			array('characterFallbacks'     , 'U+00AD'),
			array('codeMappings'           , 'AA'),
			array('currencyData'           , 'fractions'),
			array('dayPeriods'             , 'bg'),
			array('gender'                 , 'personList'),
			array('languageData'           , 'aa'),
			array('languageMatching'       , 'written'),
			array('likelySubtags'          , 'aa'),
			array('measurementData'        , 'measurementSystem'),
			array('metaZones'              , 'metazoneInfo'),
			array('numberingSystems'       , 'armn'),
			array('ordinals'               , 'af'),
			array('parentLocales'          , 'parentLocale'),
			array('plurals'                , 'af'),
			array('postalCodeData'         , 'AD'),
			array('primaryZones'           , 'CL'),
			array('references'             , 'R1000'),
			array('telephoneCodeData'      , 'AC'),
			array('territoryContainment'   , 'EU'),
			array('territoryInfo'          , 'AC'),
			array('timeData'               , 'AD'),
			array('weekData'               , 'minDays'),
			array('windowsZones'           , 'mapTimezones')
		);
	}
}
