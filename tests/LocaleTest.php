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

	public function test_get_code()
	{
		$this->assertEquals('fr', self::$locale->code);
	}

	/**
	 * @dataProvider provide_test_properties_instanceof
	 */
	public function test_properties_instanceof($property, $expected)
	{
		$locale = new Locale(get_repository(), 'fr');
		$instance = $locale->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $locale->$property);
	}

	public function provide_test_properties_instanceof()
	{
		return [

			[ 'repository', 'ICanBoogie\CLDR\Repository' ],
			[ 'calendars', 'ICanBoogie\CLDR\CalendarCollection' ],
			[ 'calendar', 'ICanBoogie\CLDR\Calendar' ],
			[ 'numbers', 'ICanBoogie\CLDR\Numbers' ]

		];
	}

	/**
	 * @expectedException \ICanBoogie\PropertyNotDefined
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

	/**
	 * @dataProvider provide_test_localize
	 */
	public function test_localize($expected, $source)
	{
		$localized = get_repository()->locales['fr']->localize($source);
		$this->assertInstanceOf($expected, $localized);
	}

	public function provide_test_localize()
	{
		return array(

			array( 'ICanBoogie\CLDR\LocalizedObject', new \ICanBoogie\DateTime ),
			array( 'ICanBoogie\CLDR\LocalizedLocale', new Locale(get_repository(), 'fr') ),
			array( 'ICanBoogie\CLDR\LocaleTest\LocalizedLocalizable', new \ICanBoogie\CLDR\LocaleTest\Localizable ),

		);
	}
}

namespace ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocalizeAwareInterface;
use ICanBoogie\CLDR\LocalizedObject;

class Localizable implements LocalizeAwareInterface
{
	static public function localize($source, Locale $locale, array $options=array())
	{
		return new LocalizedLocalizable($source, $locale, $options);
	}
}

class LocalizedLocalizable extends LocalizedObject
{
	protected function get_formatter()
	{

	}
}
