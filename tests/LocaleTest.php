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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocaleTest extends TestCase
{
    use StringHelpers;

	private static Locale $locale;

	public static function setupBeforeClass(): void
	{
		self::$locale = new Locale(get_repository(), 'fr');
	}

	public function test_get_code(): void
	{
		$this->assertEquals('fr', self::$locale->code);
	}

	#[DataProvider('provide_test_get_language')]
	public function test_get_language(string $locale_code, string $expected): void
	{
		$locale = new Locale(get_repository(), $locale_code);

		$this->assertEquals($expected, $locale->language);
	}

	public static function provide_test_get_language(): array
	{
		return [

			[ 'fr', 'fr' ],
			[ 'fr-FR', 'fr' ],
			[ 'fr-FR-u-ca-gregorian', 'fr' ],

		];
	}

	#[DataProvider('provide_test_properties_instanceof')]
	public function test_properties_instanceof(string $property, string $expected): void
	{
		$locale = new Locale(get_repository(), 'fr');
		$instance = $locale->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $locale->$property);
	}

	public static function provide_test_properties_instanceof(): array
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

	#[DataProvider('provide_test_sections')]
	public function test_sections(string $section, string $key): void
	{
		$section_data = self::$locale[$section];
		$this->assertIsArray($section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	public static function provide_test_sections(): array
	{
		return [

			[ 'ca-buddhist'            , 'months' ],
			[ 'ca-chinese'             , 'months' ],
			[ 'ca-coptic'              , 'months' ],
			[ 'ca-dangi'               , 'months' ],
			[ 'ca-ethiopic'            , 'months' ],
			[ 'ca-hebrew'              , 'months' ],
			[ 'ca-indian'              , 'months' ],
			[ 'ca-islamic'             , 'months' ],
			[ 'ca-japanese'            , 'months' ],
			[ 'ca-persian'             , 'months' ],
			[ 'ca-roc'                 , 'months' ],
			[ 'ca-generic'             , 'months' ],
			[ 'ca-gregorian'           , 'months' ],
			[ 'dateFields'             , 'era' ],
			[ 'timeZoneNames'          , 'hourFormat' ],
			[ 'languages'              , 'aa' ],
			[ 'localeDisplayNames'     , 'localeDisplayPattern' ],
			[ 'scripts'                , 'Arab' ],
			[ 'territories'            , 'AC' ],
			[ 'variants'               , 'ALUKU' ],
			[ 'characters'             , 'exemplarCharacters' ],
			[ 'contextTransforms'      , 'day-format-except-narrow' ],
			[ 'delimiters'             , 'quotationStart' ],
			[ 'layout'                 , 'orientation' ],
			[ 'listPatterns'           , 'listPattern-type-standard' ],
			[ 'posix'                  , 'messages' ],
			[ 'currencies'             , 'ADP' ],
			[ 'numbers'                , 'defaultNumberingSystem' ],
			[ 'measurementSystemNames' , 'metric' ],
			[ 'units'                  , 'long' ],

		];
	}

	#[DataProvider('provide_test_localize')]
	public function test_localize(string $expected, object $source): void
	{
		$localized = self::$locale->localize($source);
		$this->assertInstanceOf($expected, $localized);
	}

	public static function provide_test_localize(): array
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
			self::$locale->format_number(123456.78)
		);
	}

	public function test_format_percent(): void
	{
		$this->assertStringSame(
			"12 %",
			self::$locale->format_percent(.1234)
		);
	}

	public function test_format_currency(): void
	{
		$this->assertStringSame(
			"123 456,78 €",
			self::$locale->format_currency(123456.78, 'EUR')
		);
	}

	public function test_format_list(): void
	{
		$this->assertSame(
			"lundi, mardi et mercredi",
			self::$locale->format_list([ "lundi", "mardi", "mercredi" ])
		);
	}

	public function test_context_transform(): void
	{
		$this->assertEquals(
			"Juin",
			self::$locale->context_transform(
				"juin",
				ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
				ContextTransforms::TYPE_STAND_ALONE
			)
		);
	}
}
