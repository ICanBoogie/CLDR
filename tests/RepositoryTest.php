<?php

namespace Test\ICanBoogie\CLDR;

use ICanBoogie\CLDR\CurrencyFormatter;
use ICanBoogie\CLDR\ListFormatter;
use ICanBoogie\CLDR\Locale\ListPattern;
use ICanBoogie\CLDR\LocaleId;
use ICanBoogie\CLDR\LocaleNotAvailable;
use ICanBoogie\CLDR\NumberFormatter;
use ICanBoogie\CLDR\Plurals;
use ICanBoogie\CLDR\Provider;
use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\Supplemental;
use ICanBoogie\CLDR\TerritoryCode;
use ICanBoogie\CLDR\TerritoryNotDefined;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class RepositoryTest extends TestCase
{
	private Repository $sut;

	protected function setUp(): void
	{
		$this->sut = get_repository();
	}

	/**
	 * @param class-string $expected
	 */
	#[DataProvider('provide_test_properties_instanceof')]
	public function test_properties_instanceof(string $property, string $expected): void
	{
		$sut = $this->sut;
		$instance = $sut->$property;
		$this->assertInstanceOf($expected, $instance);
		$this->assertSame($instance, $sut->$property);
	}

	/**
	 * @phpstan-ignore-next-line
	 */
	public static function provide_test_properties_instanceof(): array
	{
		return [

			[ 'provider', Provider::class ],
			[ 'supplemental', Supplemental::class ],
			[ 'number_formatter', NumberFormatter::class ],
			[ 'currency_formatter', CurrencyFormatter::class ],
			[ 'list_formatter', ListFormatter::class ],
			[ 'plurals', Plurals::class ],

		];
	}

	public function test_format_number(): void
	{
		$this->assertSame(
			"4,123.37",
			$this->sut->format_number(4123.37, "#,#00.#0")
		);
	}

	public function test_format_currency(): void
	{
		$this->assertSame(
			"$4,123.37",
			$this->sut->format_currency(4123.37, "¤#,#00.#0", null, '$')
		);
	}

	public function test_format_list(): void
	{
		$list = [ 'one', 'two', 'three' ];
		$list_pattern = ListPattern::from([

			'2' => "{0} and {1}",
			'start' => "{0}, {1}",
			'middle' => "{0}, {1}",
			'end' => "{0}, and {1}",

		]);

		$this->assertSame("one, two, and three", $this->sut->format_list($list, $list_pattern));
	}

	public function test_locale_for_using_string(): void
	{
		$actual = $this->sut->locale_for('fr-BE');

		$this->assertEquals('fr-BE', $actual->id->value);
	}

	public function test_locale_for_using_id(): void
	{
		$actual = $this->sut->locale_for(LocaleId::of('fr-BE'));

		$this->assertEquals('fr-BE', $actual->id->value);
	}

	public function test_locale_for_fails_on_unavailable_id(): void
	{
		$this->expectException(LocaleNotAvailable::class);

		$this->sut->locale_for('foo');
	}

	public function test_territory_for_using_string(): void
	{
		$actual = $this->sut->territory_for('CA');

		$this->assertEquals('CA', $actual->code->value);
	}

	public function test_territory_for_using_code(): void
	{
		$actual = $this->sut->territory_for(TerritoryCode::of('CA'));

		$this->assertEquals('CA', $actual->code->value);
	}

	public function test_territory_for_fails_on_undefined_code(): void
	{
		$this->expectException(TerritoryNotDefined::class);

		$this->sut->territory_for('foo');
	}
}
