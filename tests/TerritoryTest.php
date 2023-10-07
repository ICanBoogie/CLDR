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

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TerritoryTest extends TestCase
{
	public function test_get_info(): void
	{
		$territory = new Territory(get_repository(), 'FR');
		$this->assertIsArray($territory->info);
	}

	public function test_get_containment(): void
	{
		$territory = new Territory(get_repository(), 'EU');
		$this->assertIsArray($territory->containment);
	}

	public function test_is_containing(): void
	{
		$territory = new Territory(get_repository(), 'EU');

		$this->assertTrue($territory->is_containing('FR'));
		$this->assertFalse($territory->is_containing('TA'));
	}

	#[DataProvider('provide_test_get_currency')]
	public function test_get_currency(string $expected, string $territory_code): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->currency);
	}

	public static function provide_test_get_currency(): array
	{
		return [

			[ 'EUR', 'FR' ],
			[ 'MMK', 'MM' ],
			[ 'USD', 'US' ]

		];
	}

	/**
	 * @param mixed $date
	 */
	#[DataProvider('provide_test_currency_at')]
	public function test_currency_at(string $expected, string $territory_code, $date): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->currency_at($date));
	}

	public static function provide_test_currency_at(): array
	{
		return [

			[ 'EUR', 'FR', null ],
			[ 'EUR', 'FR', 'now' ],
			[ 'EUR', 'FR', new \DateTime() ],
			[ 'FRF', 'FR', '1960-01-01' ],
			[ 'FRF', 'FR', '1977-06-06' ],
			[ 'FRF', 'FR', new \DateTime('1977-06-06') ],
			[ 'USS', 'US', new \DateTime('1234-06-06') ],
			[ 'USD', 'US', '1792-01-01' ]

		];
	}

	#[DataProvider('provide_test_get_language')]
	public function test_get_language(string $expected, string $territory_code): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertSame($expected, $territory->language);
	}

	public static function provide_test_get_language(): array
	{
		return [

			[ 'fr', 'FR' ],
			[ 'en', 'US' ],
			[ 'es', 'ES' ]

		];
	}

	public function test_get_population(): void
	{
		$territory = new Territory(get_repository(), 'ES');
		$this->assertNotEmpty($territory->population);
	}

	#[DataProvider('provide_test_name_as')]
	public function test_name_as(string $expected, string $territory_code, string $locale_code): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->name_as($locale_code));
	}

	public static function provide_test_name_as(): array
	{
		return [

			[ "France",  "FR", "fr" ],
			[ "France",  "FR", "fr-BE" ],
			[ "Francia", "FR", "it" ],
			[ "フランス", "FR", "ja" ]

		];
	}

	#[DataProvider('provide_test_get_name_as')]
	public function test_get_name_as(string $expected, string $territory_code, string $locale_code): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->{ 'name_as_' . $locale_code });
	}

	public static function provide_test_get_name_as(): array
	{
		return [

			[ "France",  "FR", "fr" ],
			[ "France",  "FR", "fr_BE" ],
			[ "Francia", "FR", "it" ],
			[ "フランス", "FR", "ja" ]

		];
	}

	#[DataProvider('provide_test_get_property')]
	public function test_get_property(string $expected, string $territory_code, string $property): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($expected, $territory->$property);
	}

	/**
	 * @see https://github.com/unicode-org/cldr-json/blob/41.0.0/cldr-json/cldr-core/supplemental/weekData.json
	 */
	public static function provide_test_get_property(): array
	{
		return [

			# first_day

			[ "mon",  "FR", 'first_day' ],
			[ "sat",  "EG", 'first_day' ],
			[ "sun",  "BS", 'first_day' ],
			[ "fri",  "MV", 'first_day' ],

			# weekend_start

			[ "sat",  "FR", 'weekend_start' ],
			[ "sat",  "AE", 'weekend_start' ],
			[ "thu",  "AF", 'weekend_start' ],
			[ "sun",  "IN", 'weekend_start' ],

			# weekend_end

			[ "sun",  "FR", 'weekend_end' ],
			[ "sun",  "AE", 'weekend_end' ],
			[ "fri",  "AF", 'weekend_end' ]

		];
	}

	public function test_to_string(): void
	{
		$territory_code = 'US';
		$territory = new Territory(get_repository(), $territory_code);
		$this->assertEquals($territory_code, (string) $territory);
	}

	public function test_localize(): void
	{
		$territory = new Territory(get_repository(), 'FR');

		$this->assertInstanceOf(LocalizedTerritory::class, $territory->localize('fr'));
	}
}
