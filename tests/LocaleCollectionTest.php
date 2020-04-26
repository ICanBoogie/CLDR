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

use BadMethodCallException;
use ICanBoogie\OffsetNotWritable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LocaleCollectionTest extends TestCase
{
	/**
	 * @var LocaleCollection
	 */
	static private $sut;

	static public function setupBeforeClass(): void
	{
		self::$sut = new LocaleCollection(get_repository());
	}

	public function test_offsetExists()
	{
		$this->expectException(BadMethodCallException::class);
		self::$sut->offsetExists('fr');
	}

	public function test_offsetSet()
	{
		$this->expectException(OffsetNotWritable::class);
		self::$sut['fr'] = null;
	}

	public function test_offsetUnset()
	{
		$this->expectException(OffsetNotWritable::class);
		unset(self::$sut['fr']);
	}

	public function test_existing_locale()
	{
		$this->assertInstanceOf(Locale::class, self::$sut['fr']);
		$this->assertInstanceOf(Locale::class, self::$sut['en']);
	}

	/**
	 * @test
	 */
	public function should_fail_with_empty_locale()
	{
		$this->expectExceptionMessage("Locale code should not be empty.");
		$this->expectException(InvalidArgumentException::class);
		self::$sut[''];
	}

	/**
	 * @test
	 */
	public function should_fail_with_undefined_locale()
	{
		$this->expectExceptionMessage("Locale is not available: madonna.");
		$this->expectException(InvalidArgumentException::class);
		self::$sut['madonna'];
	}
}
