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

class LocaleTest extends \PHPUnit_Framework_TestCase
{
	static private $locale;

	static public function setupBeforeClass()
	{
		self::$locale = new Locale(get_repository(), 'fr');
	}

	public function test_get_repository()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Repository', self::$locale->repository);
	}

	public function test_get_identity()
	{
		$this->assertEquals('fr', self::$locale->identity);
	}

	public function test_get_calendars()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\CalendarCollection', self::$locale->calendars);
	}

	public function test_get_calendar()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Calendar', self::$locale->calendar);
	}

	/**
	 * @expectedException ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined_property()
	{
		self::$locale->undefined_property;
	}

	/**
	 * @dataProvider provide_test_sections
	 */
	public function test_sections($section, $path, $key)
	{
		$section_data = self::$locale[$section];
		$this->assertInternalType('array', $section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	public function provide_test_sections()
	{
		return array
		(
			array('ca-buddhist'            , 'dates/calendars/buddhist'                 , 'months'),
			array('ca-chinese'             , 'dates/calendars/chinese'                  , 'months'),
			array('ca-coptic'              , 'dates/calendars/coptic'                   , 'months'),
			array('ca-dangi'               , 'dates/calendars/dangi'                    , 'months'),
			array('ca-ethiopic-amete-alem' , 'dates/calendars/ethiopic-amete-alem'      , 'months'),
			array('ca-ethiopic'            , 'dates/calendars/ethiopic'                 , 'months'),
			array('ca-generic'             , 'dates/calendars/generic'                  , 'months'),
			array('ca-gregorian'           , 'dates/calendars/gregorian'                , 'months'),
			array('ca-hebrew'              , 'dates/calendars/hebrew'                   , 'months'),
			array('ca-indian'              , 'dates/calendars/indian'                   , 'months'),
			array('ca-islamic-civil'       , 'dates/calendars/islamic-civil'            , 'months'),
			array('ca-islamic-rgsa'        , 'dates/calendars/islamic-rgsa'             , 'months'),
			array('ca-islamic-tbla'        , 'dates/calendars/islamic-tbla'             , 'months'),
			array('ca-islamic-umalqura'    , 'dates/calendars/islamic-umalqura'         , 'months'),
			array('ca-islamic'             , 'dates/calendars/islamic'                  , 'months'),
			array('ca-japanese'            , 'dates/calendars/japanese'                 , 'months'),
			array('ca-persian'             , 'dates/calendars/persian'                  , 'months'),
			array('ca-roc'                 , 'dates/calendars/roc'                      , 'months'),
			array('characters'             , 'characters'                               , 'exemplarCharacters'),
			array('contextTransforms'      , 'contextTransforms'                        , 'day-format-except-narrow'),
			array('currencies'             , 'numbers/currencies'                       , 'ADP'),
			array('dateFields'             , 'dates/fields'                             , 'era'),
			array('delimiters'             , 'delimiters'                               , 'quotationStart'),
			array('languages'              , 'localeDisplayNames/languages'             , 'aa'),
			array('layout'                 , 'layout'                                   , 'orientation'),
			array('listPatterns'           , 'listPatterns'                             , 'listPattern-type-standard'),
			array('localeDisplayNames'     , 'localeDisplayNames'                       , 'localeDisplayPattern'),
			array('measurementSystemNames' , 'localeDisplayNames/measurementSystemNames', 'metric'),
			array('numbers'                , 'numbers'                                  , 'defaultNumberingSystem'),
			array('posix'                  , 'posix'                                    , 'messages'),
			array('scripts'                , 'localeDisplayNames/scripts'               , 'Afak'),
			array('territories'            , 'localeDisplayNames/territories'           , 'AC'),
			array('timeZoneNames'          , 'dates/timeZoneNames'                      , 'hourFormat'),
			array('transformNames'         , 'localeDisplayNames/transformNames'        , 'BGN'),
			array('units'                  , 'units'                                    , 'long'),
			array('variants'               , 'localeDisplayNames/variants'              , 'ALUKU')
		);
	}
}
