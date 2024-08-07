<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\LocaleId;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocaleIdTest extends TestCase
{
	#[DataProvider('provide_test_is_locale_available')]
	public function test_is_locale_available(string $locale, bool $expected): void
	{
		$this->assertSame($expected, LocaleId::is_available($locale));
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_is_locale_available(): array
	{
		return [

			[ 'fr', true ],
			[ 'en', true ],
			[ 'fr-FR', false ],
			[ 'en-US', false ],

		];
	}
}
