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

class TerritoryNotDefinedTest extends TestCase
{
	/**
	 * @dataProvider provide_instance
	 *
	 * @param string $territory_code
	 * @param string|null $message
	 * @param string $expected_message
	 * @param Exception|null $previous
	 */
	public function test_instance($territory_code, $message, $expected_message, Exception $previous = null)
	{
		$sut = new TerritoryNotDefined($territory_code, $message, $previous);

		$this->assertSame($territory_code, $sut->territory_code);
		$this->assertSame($expected_message, $sut->getMessage());
		$this->assertSame($previous, $sut->getPrevious());
	}

	public function provide_instance()
	{
		$territory_code = 'FR';
		$previous = new Exception;

		return [

			"should format a message" => [
				$territory_code,
				null,
				"Territory not defined for code: $territory_code.",
				null,
			],

			"should use custom message" => [
				$territory_code,
				$message = "Madonna",
				$message,
				$previous,
			],

		];
	}
}
