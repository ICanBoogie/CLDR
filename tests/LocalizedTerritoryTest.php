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

class LocalizedTerritoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provide_test_get_name
	 */
	public function test_get_name($locale_code, $territory_code, $expected)
	{
		$territory = new Territory(get_repository(), $territory_code);
		$localized = new LocalizedTerritory($territory, get_repository()->locales[$locale_code]);

		$this->assertEquals($expected, $localized->name);
	}

	public function provide_test_get_name()
	{
		return array(

			array( 'fr', 'AC', "Île de l’Ascension" ),
			array( 'en', 'AC', "Ascension Island" ),
			array( 'ja', 'AC', "アセンション島" ),

		);
	}
}