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

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class LocaleTest extends TestCase
{
    use StringHelpers;

	/**
	 * @var Locale
	 */
	static private $locale;

	static public function setupBeforeClass(): void
	{
		self::$locale = new Locale(get_repository(), 'fr');
	}

	public function test_get_code(): void
	{
		$this->assertEquals('fr', self::$locale->code);
	}

	/**
	 * @dataProvider provide_test_get_language
	 */
	public function test_get_language(string $locale_code, string $expected): void
	{
		$locale = new Locale(get_repository(), $locale_code);

		$this->assertEquals($expected, $locale->language);
	}

	public function provide_test_get_language(): array
	{
		return [

			[ 'fr', 'fr' ],
			[ 'fr-FR', 'fr' ],
			[ 'fr-FR-u-ca-gregorian', 'fr' ],

		];
	}

	/**
	 * @dataProvider provide_test_properties_instanceof
	 */
	public function test_properties_instanceof(string $property, string $expected): void
	{
		$locale = new Locale(get_repository(), 'fr');
		$instance = $locale->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $locale->$property);
	}

	public function provide_test_properties_instanceof(): array
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
	 */
	public function test_sections(string $section, string $path, string $key): void
	{
		$section_data = self::$locale[$section];
		$this->assertIsArray($section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	public function provide_test_sections(): array
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
	 * @param object $source
	 */
	public function test_localize(string $expected, $source): void
	{
		$localized = get_repository()->locales['fr']->localize($source);
		$this->assertInstanceOf($expected, $localized);
	}

	public function provide_test_localize(): array
	{
		return [

			[ LocalizedObject::class, new \DateTime ],
			[ LocalizedLocale::class, new Locale(get_repository(), 'fr') ],
			[ LocalizedListFormatter::class, new ListFormatter ],
			[ LocalizedNumberFormatter::class, new NumberFormatter ],
			[ LocaleTest\LocalizedLocalizableSample::class, new \ICanBoogie\CLDR\LocaleTest\LocalizableSample ]

		];
	}

	public function test_empty_identifier(): void
	{
		$this->expectException(InvalidArgumentException::class);
		new Locale(get_repository(), '');
	}

	public function test_format_number(): void
	{
	    $s1 = Spaces::NARROW_NO_BREAK_SPACE;

		$this->assertStringSame(
			"123{$s1}456,78",
			(new Locale(get_repository(), 'fr'))->format_number(123456.78)
		);
	}

	public function test_format_percent(): void
	{
		$this->assertStringSame(
			"12 %",
			(new Locale(get_repository(), 'fr'))->format_percent(.1234)
		);
	}

	public function test_format_currency(): void
	{
		$this->assertStringSame(
			"123 456,78 €",
			(new Locale(get_repository(), 'fr'))->format_currency(123456.78, 'EUR')
		);
	}

	public function test_format_list(): void
	{
		$this->assertSame(
			"lundi, mardi et mercredi",
			(new Locale(get_repository(), 'fr'))->format_list([ "lundi", "mardi", "mercredi" ])
		);
	}

	public function test_context_transform(): void
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
