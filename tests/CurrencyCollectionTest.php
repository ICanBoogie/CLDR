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

use ICanBoogie\OffsetNotWritable;
use PHPUnit\Framework\TestCase;

final class CurrencyCollectionTest extends TestCase
{
	/**
	 * @var CurrencyCollection
	 */
	private $sut;

	protected function setUp(): void
	{
		$this->sut = new CurrencyCollection(get_repository());
	}

	public function test_offset_exists(): void
	{
		$this->assertTrue(isset($this->sut['EUR']));
		$this->assertTrue(isset($this->sut['USD']));
		$this->assertFalse(isset($this->sut['ABC']));
	}

	public function test_offset_get(): void
	{
		$currency = $this->sut['EUR'];

		$this->assertInstanceOf(Currency::class, $currency);
		$this->assertEquals('EUR', $currency->code);
		$this->assertSame($currency, $this->sut['EUR']);
	}

	public function test_offset_get_undefined(): void
	{
		$this->expectExceptionMessage("Currency not defined for code: ABC.");
		$this->expectException(CurrencyNotDefined::class);
		$this->sut['ABC'];
	}

	public function test_offset_set(): void
	{
		$this->expectException(OffsetNotWritable::class);
		$this->sut['EUR'] = null;
	}

	public function test_offset_unset(): void
	{
		$this->expectException(OffsetNotWritable::class);
		unset($this->sut['EUR']);
	}

	public function test_assert_defined_failure(): void
    {
	    $this->expectExceptionMessage("Currency not defined for code: MADONNA.");
	    $this->expectException(CurrencyNotDefined::class);
	    $this->sut->assert_defined('MADONNA');
    }

    public function test_assert_defined_success(): void
    {
        $this->sut->assert_defined('EUR');
		$this->assertTrue(true);
    }
}
