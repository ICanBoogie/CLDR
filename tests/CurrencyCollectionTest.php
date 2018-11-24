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
	private $sut;

	protected function setUp()
	{
		$this->sut = new CurrencyCollection(get_repository());
	}

	public function test_offset_exists()
	{
		$this->assertTrue(isset($this->sut['EUR']));
		$this->assertTrue(isset($this->sut['USD']));
		$this->assertFalse(isset($this->sut['ABC']));
	}

	public function test_offset_get()
	{
		$currency = $this->sut['EUR'];

		$this->assertInstanceOf(Currency::class, $currency);
		$this->assertEquals('EUR', $currency->code);
		$this->assertSame($currency, $this->sut['EUR']);
	}

	/**
	 * @expectedException \ICanBoogie\CLDR\CurrencyNotDefined
     * @expectedExceptionMessage Currency not defined for code: ABC.
	 */
	public function test_offset_get_undefined()
	{
		$this->sut['ABC'];
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offset_set()
	{
		$this->sut['EUR'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offset_unset()
	{
		unset($this->sut['EUR']);
	}

    /**
     * @expectedException \ICanBoogie\CLDR\CurrencyNotDefined
     * @expectedExceptionMessage Currency not defined for code: MADONNA.
     */
    public function test_assert_defined_failure()
    {
        $this->sut->assert_defined('MADONNA');
    }

    public function test_assert_defined_success()
    {
        $this->sut->assert_defined('EUR');
    }
}
