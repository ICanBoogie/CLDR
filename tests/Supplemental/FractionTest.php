<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Supplemental;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class FractionTest extends TestCase
{
	/**
	 * @phpstan-ignore-next-line
	 */
	#[DataProvider('provide_from')]
	public function test_from(array $data, int $digits, int $rounding, int $cash_digits, int $cash_rounding): void
	{
		$fraction = Fraction::from($data);

		$this->assertSame($digits, $fraction->digits);
		$this->assertSame($rounding, $fraction->rounding);
		$this->assertSame($cash_digits, $fraction->cash_digits);
		$this->assertSame($cash_rounding, $fraction->cash_rounding);
	}

	public static function provide_from(): array
	{
		return [

			[
				[],
				2,
				0,
				2,
				0
			],

			[
				[ '_digits' => '2', '_rounding' => '50', '_cashDigits' => '3', '_cashRounding' => '51' ],
				2,
				50,
				3,
				51
			],

			[
				[ '_digits' => '2', '_rounding' => '50' ],
				2,
				50,
				2,
				50
			],

		];
	}
}
