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

class TerritoryCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var TerritoryCollection
	 */
	private $sut;

	protected function setUp()
	{
		$this->sut = new TerritoryCollection(get_repository());
	}

	public function test_offsetExists()
	{
		$this->assertTrue(isset($this->sut['FR']));
		$this->assertFalse(isset($this->sut['MADONNA']));
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetSet()
	{
		$this->sut['FR'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetUnset()
	{
		unset($this->sut['FR']);
	}

	public function test_defined()
	{
		$this->assertInstanceOf(Territory::class, $this->sut['FR']);
		$this->assertInstanceOf(Territory::class, $this->sut['US']);
	}

    /**
     * @expectedException \ICanBoogie\CLDR\TerritoryNotDefined
     * @expectedExceptionMessage Territory not defined for code: MADONNA.
     */
    public function test_assert_defined_failure()
    {
        $this->sut->assert_defined('MADONNA');
    }

    public function test_assert_defined_success()
    {
        $this->sut->assert_defined('FR');
    }
}
