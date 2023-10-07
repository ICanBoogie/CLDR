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

final class LocalizedTerritoryTest extends TestCase
{
	#[DataProvider('provide_test_get_name')]
	public function test_get_name(string $locale_code, string $territory_code, string $expected): void
	{
		$territory = new Territory(get_repository(), $territory_code);
		$localized = new LocalizedTerritory($territory, get_repository()->locales[$locale_code]);

		$this->assertEquals($expected, $localized->name);
	}

	public static function provide_test_get_name(): array
	{
		return [

			[ 'fr', 'AC', "Île de l’Ascension" ],
			[ 'en', 'AC', "Ascension Island" ],
			[ 'ja', 'AC', "アセンション島" ],

		];
	}
}
