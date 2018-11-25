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

class LocaleTest extends \PHPUnit\Framework\TestCase
{
    use StringHelpers;

	/**
	 * @var Locale
	 */
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
	 * @dataProvider provide_test_get_language
	 *
	 * @param string $locale_code
	 * @param string $expected
	 */
	public function test_get_language($locale_code, $expected)
	{
		$locale = new Locale(get_repository(), $locale_code);

		$this->assertEquals($expected, $locale->language);
	}

	public function provide_test_get_language()
	{
		return [

			[ 'fr', 'fr' ],
			[ 'fr-FR', 'fr' ],
			[ 'fr-FR-u-ca-gregorian', 'fr' ],

		];
	}

	/**
	 * @dataProvider provide_test_properties_instanceof
	 *
	 * @param string $property
	 * @param string $expected
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

			[ 'repository',         Repository::class ],
			[ 'calendars',          CalendarCollection::class ],
			[ 'calendar',           Calendar::class ],
			[ 'numbers',            Numbers::class ],
			[ 'number_formatter',   LocalizedNumberFormatter::class ],
			[ 'currency_formatter', LocalizedCurrencyFormatter::class ],
			[ 'list_formatter',     LocalizedListFormatter::class ],
			[ 'context_transforms', ContextTransforms::class ],
			[ 'units',              Units::class ],

		];
	}

	/**
	 * @dataProvider provide_test_sections
	 *
	 * @param string $section
	 * @param string $path
	 * @param string $key
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
			[ 'ca-ethiopic'            , 'dates/calendars/ethiopic'                 , 'months' ],
			[ 'ca-generic'             , 'dates/calendars/generic'                  , 'months' ],
			[ 'ca-gregorian'           , 'dates/calendars/gregorian'                , 'months' ],
			[ 'ca-hebrew'              , 'dates/calendars/hebrew'                   , 'months' ],
			[ 'ca-indian'              , 'dates/calendars/indian'                   , 'months' ],
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
			[ 'scripts'                , 'localeDisplayNames/scripts'               , 'Arab' ],
			[ 'territories'            , 'localeDisplayNames/territories'           , 'AC' ],
			[ 'timeZoneNames'          , 'dates/timeZoneNames'                      , 'hourFormat' ],
			[ 'units'                  , 'units'                                    , 'long' ],
			[ 'variants'               , 'localeDisplayNames/variants'              , 'ALUKU' ]

		];
	}

	/**
	 * @dataProvider provide_test_localize
	 *
	 * @param string $expected
	 * @param object $source
	 */
	public function test_localize($expected, $source)
	{
		$localized = get_repository()->locales['fr']->localize($source);
		$this->assertInstanceOf($expected, $localized);
	}

	public function provide_test_localize()
	{
		return [

			[ LocalizedObject::class, new \DateTime ],
			[ LocalizedLocale::class, new Locale(get_repository(), 'fr') ],
			[ LocalizedListFormatter::class, new ListFormatter ],
			[ LocalizedNumberFormatter::class, new NumberFormatter ],
			[ LocaleTest\LocalizedLocalizableSample::class, new \ICanBoogie\CLDR\LocaleTest\LocalizableSample ]

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
	    $s1 = Spaces::NARROW_NO_BREAK_SPACE;

		$this->assertStringSame(
			"123{$s1}456,78",
			(new Locale(get_repository(), 'fr'))->format_number(123456.78)
		);
	}

	public function test_format_percent()
	{
		$this->assertStringSame(
			"12 %",
			(new Locale(get_repository(), 'fr'))->format_percent(.1234)
		);
	}

	public function test_format_currency()
	{
		$this->assertStringSame(
			"123 456,78 €",
			(new Locale(get_repository(), 'fr'))->format_currency(123456.78, 'EUR')
		);
	}

	public function test_format_list()
	{
		$this->assertSame(
			"lundi, mardi et mercredi",
			(new Locale(get_repository(), 'fr'))->format_list([ "lundi", "mardi", "mercredi" ])
		);
	}

	public function test_context_transform()
	{
		$this->assertEquals(
			"Juin",
			(new Locale(get_repository(), 'fr'))->context_transform(
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE
			)
		);
	}
}
