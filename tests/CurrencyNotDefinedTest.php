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

class CurrencyNotDefinedTest extends TestCase
{
	/**
	 * @dataProvider provide_instance
	 *
	 * @param string $currency_code
	 * @param string|null $message
	 * @param string $expected_message
	 * @param Exception|null $previous
	 */
	public function test_instance($currency_code, $message, $expected_message, Exception $previous = null)
	{
		$sut = new CurrencyNotDefined($currency_code, $message, $previous);

		$this->assertSame($currency_code, $sut->currency_code);
		$this->assertSame($expected_message, $sut->getMessage());
		$this->assertSame($previous, $sut->getPrevious());
	}

	public function provide_instance()
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
