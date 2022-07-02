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

final class TerritoryNotDefinedTest extends TestCase
{
	/**
	 * @dataProvider provide_instance
	 */
	public function test_instance(string $territory_code, ?string $message, string $expected_message, Exception $previous = null): void
	{
		$sut = new TerritoryNotDefined($territory_code, $message, $previous);

		$this->assertSame($territory_code, $sut->territory_code);
		$this->assertSame($expected_message, $sut->getMessage());
		$this->assertSame($previous, $sut->getPrevious());
	}

	public function provide_instance(): array
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
