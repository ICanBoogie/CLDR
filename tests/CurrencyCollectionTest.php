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

class CurrencyCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var CurrencyCollection
	 */
	static private $instance;

	static public function setupBeforeClass()
	{
		self::$instance = get_repository()->currencies;
	}

	public function test_offset_exists()
	{
		$this->assertTrue(isset(self::$instance['EUR']));
		$this->assertTrue(isset(self::$instance['USD']));
		$this->assertFalse(isset(self::$instance['ABC']));
	}

	public function test_offset_get()
	{
		$currency = self::$instance['EUR'];

		$this->assertInstanceOf(Currency::class, $currency);
		$this->assertEquals('EUR', $currency->code);
		$this->assertSame($currency, self::$instance['EUR']);
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotDefined
	 */
	public function test_offset_get_undefined()
	{
		self::$instance['ABC'];
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offset_set()
	{
		self::$instance['EUR'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offset_unset()
	{
		unset(self::$instance['EUR']);
	}
}
