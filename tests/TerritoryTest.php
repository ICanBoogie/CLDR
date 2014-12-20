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

use ICanBoogie\DateTime;

class TerritoryTest extends \PHPUnit_Framework_TestCase
{
	public function test_get_info()
	{
		$territory = new Territory(get_repository(), 'FR');
		$this->assertInternalType('array', $territory->info);
	}

	public function test_get_containment()
	{
		$territory = new Territory(get_repository(), 'EU');
		$this->assertInternalType('array', $territory->containment);
	}

	public function test_is_containing()
	{
		$territory = new Territory(get_repository(), 'EU');

		$this->assertTrue($territory->is_containing('FR'));
		$this->assertFalse($territory->is_containing('TA'));
	}

	/**
	 * @dataProvider provide_test_get_currency
	 */
	public function test_get_currency($expected, $territory_code)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->currency);
	}

	public function provide_test_get_currency()
	{
		return [

			[ 'EUR', 'FR' ],
			[ 'MMK', 'MM' ],
			[ 'USD', 'US' ]

		];
	}

	/**
	 * @dataProvider provide_test_currency_at
	 */
	public function test_currency_at($expected, $territory_code, $date)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->currency_at($date));
	}

	public function provide_test_currency_at()
	{
		return [

			[ 'EUR', 'FR', null ],
			[ 'EUR', 'FR', 'now' ],
			[ 'EUR', 'FR', DateTime::now() ],
			[ 'FRF', 'FR', '1960-01-01' ],
			[ 'FRF', 'FR', '1977-06-06' ],
			[ 'FRF', 'FR', DateTime::from('1977-06-06') ],
			[ 'USS', 'US', DateTime::from('1234-06-06') ],
			[ 'USD', 'US', '1792-01-01' ]

		];
	}

	/**
	 * @dataProvider provide_test_get_language
	 */
	public function test_get_language($expected, $territory_code)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertSame($expected, $territory->language);
	}

	public function provide_test_get_language()
	{
		return [

			[ 'fr', 'FR' ],
			[ 'en', 'US' ],
			[ 'es', 'ES' ]

		];
	}

	public function test_get_population()
	{
		$territory = new Territory(get_repository(), 'ES');
		$this->assertNotEmpty($territory->population);
	}

	/**
	 * @dataProvider provide_test_name_as
	 */
	public function test_name_as($expected, $territory_code, $locale_code)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->name_as($locale_code));
	}

	public function provide_test_name_as()
	{
		return [

			[ "France",  "FR", "fr" ],
			[ "France",  "FR", "fr-BE" ],
			[ "Francia", "FR", "it" ],
			[ "フランス", "FR", "ja" ]

		];
	}

	/**
	 * @dataProvider provide_test_get_name_as
	 */
	public function test_get_name_as($expected, $territory_code, $locale_code)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->{ 'name_as_' . $locale_code });
	}

	public function provide_test_get_name_as()
	{
		return [

			[ "France",  "FR", "fr" ],
			[ "France",  "FR", "fr_BE" ],
			[ "Francia", "FR", "it" ],
			[ "フランス", "FR", "ja" ]

		];
	}

	/**
	 * @dataProvider provide_test_get_property
	 */
	public function test_get_property($expected, $territory_code, $property)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->$property);
	}

	public function provide_test_get_property()
	{
		return [

			# first_day

			[ "mon",  "FR", 'first_day' ],
			[ "sat",  "EG", 'first_day' ],
			[ "sun",  "BS", 'first_day' ],
			[ "fri",  "MV", 'first_day' ],

			# weekend_start

			[ "sat",  "FR", 'weekend_start' ],
			[ "fri",  "AE", 'weekend_start' ],
			[ "thu",  "AF", 'weekend_start' ],
			[ "sun",  "IN", 'weekend_start' ],

			# weekend_end

			[ "sun",  "FR", 'weekend_end' ],
			[ "sat",  "AE", 'weekend_end' ],
			[ "fri",  "AF", 'weekend_end' ]

		];
	}

	public function test_to_string()
	{
		$territory_code = 'US';
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($territory_code, (string) $territory);
	}

	public function test_localize()
	{
		$territory = new Territory(get_repository(), 'FR');

		$this->assertInstanceOf('ICanBoogie\CLDR\LocalizedTerritory', $territory->localize('fr'));
	}
}
