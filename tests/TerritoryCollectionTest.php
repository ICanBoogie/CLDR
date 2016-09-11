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
	static private $collection;

	static public function setupBeforeClass()
	{
		self::$collection = new TerritoryCollection(get_repository());
	}

	public function test_offsetExists()
	{
		$this->assertTrue(isset(self::$collection['FR']));
		$this->assertFalse(isset(self::$collection[uniqid()]));
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetSet()
	{
		self::$collection['FR'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetUnset()
	{
		unset(self::$collection['FR']);
	}

	public function test_defined()
	{
		$this->assertInstanceOf(Territory::class, self::$collection['FR']);
		$this->assertInstanceOf(Territory::class, self::$collection['US']);
	}

	public function test_undefined()
	{
		$code = uniqid();

		try
		{
			self::$collection[$code];

			$this->fail("Excepted exception");
		}
		catch (\Exception $e)
		{
			$this->assertInstanceOf(TerritoryNotDefined::class, $e);

			/* @var $e TerritoryNotDefined */

			$this->assertEquals($code, $e->territory_code);
		}
	}
}
