<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocaleId;
use ICanBoogie\CLDR\LocalizedLocale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocalizedLocaleTest extends TestCase
{
	#[DataProvider('provide_test_get_name')]
	public function test_get_name(string $locale_id, string $code, string $expected): void
	{
		$locale = new Locale(get_repository(), LocaleId::of($code));
		$localized = new LocalizedLocale($locale, locale_for($locale_id));

		$this->assertEquals($expected, $localized->name);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_get_name(): array
	{
		return [

			[ 'fr', 'fr', "français" ],
			[ 'fr', 'fr-CA', "français canadien" ],
			[ 'en', 'fr', "French" ],
			[ 'en', 'fr-CA', "Canadian French" ],
			[ 'fr', 'nl', "néerlandais" ],
			[ 'fr', 'nl-BE', "flamand" ],

		];
	}

	public function test_localize(): void
	{
		$locale = new Locale(get_repository(), LocaleId::of('fr'));
		$localized = $locale->localize(LocaleId::of('es'));
		$this->assertInstanceOf(LocalizedLocale::class, $localized);
		$this->assertEquals("francés", $localized->name);
	}
}
