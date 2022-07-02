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

final class TerritoryCollectionTest extends TestCase
{
	/**
	 * @var TerritoryCollection
	 */
	private $sut;

	protected function setUp(): void
	{
		$this->sut = new TerritoryCollection(get_repository());
	}

	public function test_offsetExists(): void
	{
		$this->assertTrue(isset($this->sut['FR']));
		$this->assertFalse(isset($this->sut['MADONNA']));
	}

	public function test_offsetSet(): void
	{
		$this->expectException(OffsetNotWritable::class);
		$this->sut['FR'] = null;
	}

	public function test_offsetUnset(): void
	{
		$this->expectException(OffsetNotWritable::class);
		unset($this->sut['FR']);
	}

	public function test_defined(): void
	{
		$this->assertInstanceOf(Territory::class, $this->sut['FR']);
		$this->assertInstanceOf(Territory::class, $this->sut['US']);
	}

	public function test_assert_defined_failure(): void
    {
	    $this->expectExceptionMessage("Territory not defined for code: MADONNA.");
	    $this->expectException(TerritoryNotDefined::class);
	    $this->sut->assert_defined('MADONNA');
    }

    public function test_assert_defined_success(): void
    {
        $this->sut->assert_defined('FR');
		$this->assertTrue(true);
    }
}
