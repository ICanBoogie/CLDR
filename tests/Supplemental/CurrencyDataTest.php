<?php

namespace Test\ICanBoogie\CLDR\Supplemental;

use ICanBoogie\CLDR\Supplemental\CurrencyData;
use ICanBoogie\CLDR\Supplemental\Fraction;
use PHPUnit\Framework\TestCase;

use function Test\ICanBoogie\CLDR\get_repository;

final class CurrencyDataTest extends TestCase
{
	private CurrencyData $sut;

	protected function setUp(): void
	{
		/* @phpstan-ignore-next-line */
		$this->sut = new CurrencyData(get_repository()->supplemental['currencyData']);

		parent::setUp();
	}

	public function test_fraction_fallback_reuse(): void
	{
		$fraction = $this->sut->fraction_for('EUR');

		$this->assertSame($fraction, $this->sut->fraction_for('Z01'));
	}

	/**
	 * @dataProvider provide_fraction_for
	 *
	 * @phpstan-ignore-next-line
	 */
	public function test_fraction_for(string $currency_code, array $expected): void
	{
		$this->assertEquals(
			Fraction::from($expected),
			$this->sut->fraction_for($currency_code)
		);
	}

	public static function provide_fraction_for(): array
	{
		return [

			[ 'ADP', [ '_rounding' => '0', '_digits' => '0' ] ],
			[ 'CZK', [ '_rounding' => '0', '_digits' => '2', '_cashRounding' => '0', '_cashDigits' => '0' ] ],
			[ 'DKK', [ '_rounding' => '0', '_digits' => '2', '_cashRounding' => '50' ] ],
			[ 'EUR', [ '_rounding' => '0', '_digits' => '2' ] ],

		];
	}
}
