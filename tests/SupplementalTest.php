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

use ICanBoogie\CLDR\Supplemental\CurrencyData;
use ICanBoogie\OffsetNotDefined;
use ICanBoogie\OffsetNotWritable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SupplementalTest extends TestCase
{
	private static Supplemental $sut;

	public static function setupBeforeClass(): void
	{
		self::$sut = get_repository()->supplemental;
	}

	#[DataProvider('provide_test_sections')]
	public function test_sections(string $section, string $key): void
	{
		$section_data = self::$sut[$section];
		$this->assertIsArray($section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	public static function provide_test_sections(): array
	{
		return [

		    [ 'aliases'                , 'languageAlias' ],
			[ 'calendarData'           , 'buddhist' ],
			[ 'calendarPreferenceData' , 'AE' ],
			[ 'characterFallbacks'     , 'U+00AD' ],
			[ 'codeMappings'           , 'AA' ],
			[ 'currencyData'           , 'fractions' ],
            [ 'dayPeriods'             , 'af' ],
			[ 'gender'                 , 'personList' ],
			[ 'grammaticalFeatures'    , 'am-targets-nominal' ],
			[ 'languageData'           , 'aa' ],
			[ 'languageGroups'         , 'aav' ],
			[ 'languageMatching'       , 'written-new' ],
			[ 'likelySubtags'          , 'aa' ],
			[ 'measurementData'        , 'measurementSystem' ],
			[ 'metaZones'              , 'metazoneInfo' ],
			[ 'numberingSystems'       , 'armn' ],
			[ 'ordinals'               , 'af' ],
			[ 'parentLocales'          , 'en-150' ],
			[ 'pluralRanges'           , 'af' ],
			[ 'plurals'                , 'af' ],
			[ 'primaryZones'           , 'CL' ],
			[ 'references'             , 'R1000' ],
			[ 'territoryContainment'   , 'EU' ],
			[ 'territoryInfo'          , 'AC' ],
			[ 'timeData'               , 'AD' ],
			[ 'unitPreferenceData'     , 'area' ],
			[ 'weekData'               , 'minDays' ],
			[ 'windowsZones'           , 'mapTimezones' ],

		];
	}

	#[DataProvider('provide_properties')]
	public function test_properties(string $property, string $expected): void
	{
		$this->assertInstanceOf($expected, $value = self::$sut->$property);
		// Make sure values are lazy created and reused
		$this->assertSame($value, self::$sut->$property);
	}

	public static function provide_properties(): array
	{
		return [

			[ 'currency_data', CurrencyData::class ],

		];
	}

	public function test_default_calendar(): void
	{
		$this->assertArrayHasKey('001', self::$sut['calendarPreferenceData']);
	}

    public function test_offset_exists(): void
    {
        $s = self::$sut;

        $this->assertTrue(isset($s['calendarPreferenceData']));
        $this->assertTrue(isset($s['numberingSystems']));
        $this->assertFalse(isset($s[uniqid()]));
    }

	public function test_should_throw_exception_when_getting_undefined_offset(): void
    {
	    $s = self::$sut;
	    $this->expectException(OffsetNotDefined::class);
        $s[uniqid()]; // @phpstan-ignore-line
    }

	public function test_should_throw_exception_in_attempt_to_set_offset(): void
    {
	    $s = self::$sut;
	    $this->expectException(OffsetNotWritable::class);
        $s['timeData'] = null;
    }

	public function test_should_throw_exception_in_attempt_to_unset_offset(): void
    {
	    $s = self::$sut;
	    $this->expectException(OffsetNotWritable::class);
        unset($s['timeData']);
    }
}
