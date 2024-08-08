<?php

namespace Test\ICanBoogie\CLDR;

use Exception;
use ICanBoogie\CLDR\CurrencyNotDefined;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CurrencyNotDefinedTest extends TestCase
{
	#[DataProvider('provide_instance')]
	public function test_instance(
		string $currency_code,
		?string $message,
		string $expected_message,
		Exception $previous = null
	): void {
		$sut = new CurrencyNotDefined($currency_code, $message, $previous);

		$this->assertSame($currency_code, $sut->currency_code);
		$this->assertSame($expected_message, $sut->getMessage());
		$this->assertSame($previous, $sut->getPrevious());
	}

	public static function provide_instance(): array
	{
		$currency_code = 'EUR';
		$previous = new Exception();

		return [

			"should format a message" => [
				$currency_code,
				null,
				"Currency not defined for code: $currency_code.",
				null,
			],

			"should use custom message" => [
				$currency_code,
				$message = "Madonna",
				$message,
				$previous,
			],

		];
	}
}
