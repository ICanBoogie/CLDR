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

class LocaleCollectionTest extends \PHPUnit\Framework\TestCase
{
	/**
	 * @var LocaleCollection
	 */
	static private $sut;

	static public function setupBeforeClass()
	{
		self::$sut = new LocaleCollection(get_repository());
	}

	/**
	 * @expectedException \BadMethodCallException
	 */
	public function test_offsetExists()
	{
		self::$sut->offsetExists('fr');
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetSet()
	{
		self::$sut['fr'] = null;
	}

	/**
	 * @expectedException \ICanBoogie\OffsetNotWritable
	 */
	public function test_offsetUnset()
	{
		unset(self::$sut['fr']);
	}

	public function test_existing_locale()
	{
		$this->assertInstanceOf(Locale::class, self::$sut['fr']);
		$this->assertInstanceOf(Locale::class, self::$sut['en']);
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Locale code should not be empty.
	 */
	public function should_fail_with_empty_locale()
	{
		self::$sut[''];
	}

	/**
	 * @test
	 * @expectedException \InvalidArgumentException
	 * @expectedExceptionMessage Locale is not available: madonna.
	 */
	public function should_fail_with_undefined_locale()
	{
		self::$sut['madonna'];
	}
}
