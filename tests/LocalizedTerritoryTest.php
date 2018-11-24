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

class LocalizedTerritoryTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @dataProvider provide_test_get_name
	 *
	 * @param string $locale_code
	 * @param string $territory_code
	 * @param string $expected
	 */
	public function test_get_name($locale_code, $territory_code, $expected)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$localized = new LocalizedTerritory($territory, get_repository()->locales[$locale_code]);

		$this->assertEquals($expected, $localized->name);
	}

	public function provide_test_get_name()
	{
		return [

			[ 'fr', 'AC', "Île de l’Ascension" ],
			[ 'en', 'AC', "Ascension Island" ],
			[ 'ja', 'AC', "アセンション島" ],

		];
	}
}
