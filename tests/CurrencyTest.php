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

class CurrencyTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Repository
	 */
	static private $repository;

	static public function setupBeforeClass()
	{
		self::$repository = get_repository();
	}

	/**
	 * @dataProvider provide_test_properties
	 *
	 * @param $code
	 * @param $property
	 * @param $expected
	 */
	public function test_properties($code, $property, $expected)
	{
		$currency = new Currency(self::$repository, $code);

		$this->assertSame($expected, $currency->$property);
	}

	public function provide_test_properties()
	{
		return [

			[ 'EUR', 'digits', 2 ],
			[ 'EUR', 'rounding', 0 ],
			[ 'EUR', 'cash_digits', null ],
			[ 'EUR', 'cash_rounding', null ],

			[ 'HUF', 'digits', 2 ],
			[ 'HUF', 'rounding', 0 ],
			[ 'HUF', 'cash_digits', 0 ],
			[ 'HUF', 'cash_rounding', 0 ],

			[ 'LYD', 'digits', 3 ],
			[ 'LYD', 'rounding', 0 ],
			[ 'LYD', 'cash_digits', null ],
			[ 'LYD', 'cash_rounding', null ],

		];
	}
}
