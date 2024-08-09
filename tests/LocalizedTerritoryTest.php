<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\LocalizedTerritory;
use ICanBoogie\CLDR\Territory;
use ICanBoogie\CLDR\TerritoryCode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedTerritoryTest extends TestCase
{
	#[DataProvider('provide_test_get_name')]
	public function test_get_name(string $locale_id, string $territory_code, string $expected): void
	{
		$territory = new Territory(get_repository(), TerritoryCode::of($territory_code));
		$localized = new LocalizedTerritory($territory, locale_for($locale_id));

		$this->assertEquals($expected, $localized->name);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_get_name(): array
	{
		return [

			[ 'fr', 'AC', "Île de l’Ascension" ],
			[ 'en', 'AC', "Ascension Island" ],
			[ 'ja', 'AC', "アセンション島" ],

		];
	}
}
