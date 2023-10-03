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

use Exception;
use PHPUnit\Framework\TestCase;

final class CurrencyNotDefinedTest extends TestCase
{
	/**
	 * @dataProvider provide_instance
	 */
	public function test_instance(string $currency_code, ?string $message, string $expected_message, Exception $previous = null)
	{
		$sut = new CurrencyNotDefined($currency_code, $message, $previous);

		$this->assertSame($currency_code, $sut->currency_code);
		$this->assertSame($expected_message, $sut->getMessage());
		$this->assertSame($previous, $sut->getPrevious());
	}

	public static function provide_instance(): array
	{
		$currency_code = 'EUR';
		$previous = new Exception;

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
