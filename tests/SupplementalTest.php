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
use PHPUnit\Framework\TestCase;

final class SupplementalTest extends TestCase
{
	/**
	 * @var Supplemental
	 */
	static private $stu;

	static public function setupBeforeClass(): void
	{
		self::$stu = get_repository()->supplemental;
	}

	/**
	 * @dataProvider provide_test_sections
	 */
	public function test_sections(string $section, string $key): void
	{
		$section_data = self::$stu[$section];
		$this->assertIsArray($section_data);
		$this->assertArrayHasKey($key, $section_data);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
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

	/**
	 * @dataProvider provide_properties
	 */
	public function test_properties(string $property, string $expected): void
	{
		$this->assertInstanceOf($expected, $value = self::$stu->$property);
		// Make sure values are lazy created and reused
		$this->assertSame($value, self::$stu->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_properties(): array
	{
		return [

			[ 'currency_data', CurrencyData::class ],

		];
	}

	public function test_default_calendar(): void
	{
		$this->assertArrayHasKey('001', self::$stu['calendarPreferenceData']);
	}

    public function test_offset_exists(): void
    {
        $s = self::$stu;

        $this->assertTrue(isset($s['calendarPreferenceData']));
        $this->assertTrue(isset($s['numberingSystems']));
        $this->assertFalse(isset($s[uniqid()]));
    }

	public function test_should_throw_exception_when_getting_undefined_offset(): void
    {
	    $s = self::$stu;
	    $this->expectException(OffsetNotDefined::class);
        $s[uniqid()]; // @phpstan-ignore-line
    }

	public function test_should_throw_exception_in_attempt_to_set_offset(): void
    {
	    $s = self::$stu;
	    $this->expectException(OffsetNotWritable::class);
        $s['timeData'] = null;
    }

	public function test_should_throw_exception_in_attempt_to_unset_offset(): void
    {
	    $s = self::$stu;
	    $this->expectException(OffsetNotWritable::class);
        unset($s['timeData']);
    }
}
