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

			[ 'repository',       'ICanBoogie\CLDR\Repository' ],
			[ 'calendars',        'ICanBoogie\CLDR\CalendarCollection' ],
			[ 'calendar',         'ICanBoogie\CLDR\Calendar' ],
			[ 'numbers',          'ICanBoogie\CLDR\Numbers' ],
			[ 'number_formatter', 'ICanBoogie\CLDR\LocalizedNumberFormatter' ],
			[ 'list_formatter',   'ICanBoogie\CLDR\LocalizedListFormatter' ]

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
		return [

			[ 'ca-buddhist'            , 'dates/calendars/buddhist'                 , 'months' ],
			[ 'ca-chinese'             , 'dates/calendars/chinese'                  , 'months' ],
			[ 'ca-coptic'              , 'dates/calendars/coptic'                   , 'months' ],
			[ 'ca-dangi'               , 'dates/calendars/dangi'                    , 'months' ],
			[ 'ca-ethiopic-amete-alem' , 'dates/calendars/ethiopic-amete-alem'      , 'months' ],
			[ 'ca-ethiopic'            , 'dates/calendars/ethiopic'                 , 'months' ],
			[ 'ca-generic'             , 'dates/calendars/generic'                  , 'months' ],
			[ 'ca-gregorian'           , 'dates/calendars/gregorian'                , 'months' ],
			[ 'ca-hebrew'              , 'dates/calendars/hebrew'                   , 'months' ],
			[ 'ca-indian'              , 'dates/calendars/indian'                   , 'months' ],
			[ 'ca-islamic-civil'       , 'dates/calendars/islamic-civil'            , 'months' ],
			[ 'ca-islamic-rgsa'        , 'dates/calendars/islamic-rgsa'             , 'months' ],
			[ 'ca-islamic-tbla'        , 'dates/calendars/islamic-tbla'             , 'months' ],
			[ 'ca-islamic-umalqura'    , 'dates/calendars/islamic-umalqura'         , 'months' ],
			[ 'ca-islamic'             , 'dates/calendars/islamic'                  , 'months' ],
			[ 'ca-japanese'            , 'dates/calendars/japanese'                 , 'months' ],
			[ 'ca-persian'             , 'dates/calendars/persian'                  , 'months' ],
			[ 'ca-roc'                 , 'dates/calendars/roc'                      , 'months' ],
			[ 'characters'             , 'characters'                               , 'exemplarCharacters' ],
			[ 'contextTransforms'      , 'contextTransforms'                        , 'day-format-except-narrow' ],
			[ 'currencies'             , 'numbers/currencies'                       , 'ADP' ],
			[ 'dateFields'             , 'dates/fields'                             , 'era' ],
			[ 'delimiters'             , 'delimiters'                               , 'quotationStart' ],
			[ 'languages'              , 'localeDisplayNames/languages'             , 'aa' ],
			[ 'layout'                 , 'layout'                                   , 'orientation' ],
			[ 'listPatterns'           , 'listPatterns'                             , 'listPattern-type-standard' ],
			[ 'localeDisplayNames'     , 'localeDisplayNames'                       , 'localeDisplayPattern' ],
			[ 'measurementSystemNames' , 'localeDisplayNames/measurementSystemNames', 'metric' ],
			[ 'numbers'                , 'numbers'                                  , 'defaultNumberingSystem' ],
			[ 'posix'                  , 'posix'                                    , 'messages' ],
			[ 'scripts'                , 'localeDisplayNames/scripts'               , 'Afak' ],
			[ 'territories'            , 'localeDisplayNames/territories'           , 'AC' ],
			[ 'timeZoneNames'          , 'dates/timeZoneNames'                      , 'hourFormat' ],
			[ 'transformNames'         , 'localeDisplayNames/transformNames'        , 'BGN' ],
			[ 'units'                  , 'units'                                    , 'long' ],
			[ 'variants'               , 'localeDisplayNames/variants'              , 'ALUKU' ]

		];
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
		return [

			[ 'ICanBoogie\CLDR\LocalizedObject', new \ICanBoogie\DateTime ],
			[ 'ICanBoogie\CLDR\LocalizedLocale', new Locale(get_repository(), 'fr') ],
			[ 'ICanBoogie\CLDR\LocalizedListFormatter', new ListFormatter ],
			[ 'ICanBoogie\CLDR\LocalizedNumberFormatter', new NumberFormatter ],
			[ 'ICanBoogie\CLDR\LocaleTest\LocalizedLocalizable', new \ICanBoogie\CLDR\LocaleTest\Localizable ]

		];
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function test_empty_identifier()
	{
		new Locale(get_repository(), '');
	}

	public function test_format_number()
	{
		$this->assertEquals("123 456,78", (new Locale(get_repository(), 'fr'))->format_number(123456.78));
	}

	public function test_format_list()
	{
		$this->assertEquals("lundi, mardi et mercredi", (new Locale(get_repository(), 'fr'))->format_list([ "lundi", "mardi", "mercredi" ]));
	}
}

namespace ICanBoogie\CLDR\LocaleTest;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocalizeAwareInterface;
use ICanBoogie\CLDR\LocalizedObject;

class Localizable implements LocalizeAwareInterface
{
	static public function localize($source, Locale $locale, array $options=[])
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
