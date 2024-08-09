<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\LocaleId;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LocaleIdTest extends TestCase
{
	#[DataProvider('provide_test_is_locale_available')]
	public function test_is_locale_available(string $locale_id, bool $expected): void
	{
		$this->assertSame($expected, LocaleId::is_available($locale_id));
	}

	/** @phpstan-ignore-next-line */
	public static function provide_test_is_locale_available(): array
	{
		return [

			[ 'fr', true ],
			[ 'en', true ],
			[ 'en-AG', true ],
			[ 'fr-FR', false ],
			[ 'en-US', false ],

		];
	}

	public function test_of_fails_on_unavailable_id(): void
	{
		$this->expectException(InvalidArgumentException::class);

		LocaleId::of('fr-FR');
	}

	public function test_of_use_parent(): void
	{
		$locale = LocaleId::of('en-AG');

		$this->assertEquals('en-001', $locale->value);
	}
}
