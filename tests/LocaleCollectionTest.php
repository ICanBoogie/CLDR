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

final class LocaleCollectionTest extends TestCase
{
	/**
	 * @var LocaleCollection
	 */
	static private $sut;

	static public function setupBeforeClass(): void
	{
		self::$sut = new LocaleCollection(get_repository());
	}

	public function test_offsetExists(): void
	{
		$this->expectException(BadMethodCallException::class);
		self::$sut->offsetExists('fr');
	}

	public function test_offsetSet(): void
	{
		$this->expectException(OffsetNotWritable::class);
		self::$sut['fr'] = null;
	}

	public function test_offsetUnset(): void
	{
		$this->expectException(OffsetNotWritable::class);
		unset(self::$sut['fr']);
	}

	public function test_existing_locale(): void
	{
		$this->assertInstanceOf(Locale::class, self::$sut['fr']);
		$this->assertInstanceOf(Locale::class, self::$sut['en']);
	}

	/**
	 * @test
	 */
	public function should_fail_with_empty_locale(): void
	{
		$this->expectExceptionMessage("Locale code should not be empty.");
		$this->expectException(InvalidArgumentException::class);
		self::$sut['']; // @phpstan-ignore-line
	}

	/**
	 * @test
	 */
	public function should_fail_with_undefined_locale(): void
	{
		$this->expectExceptionMessage("Locale is not available: madonna.");
		$this->expectException(InvalidArgumentException::class);
		self::$sut['madonna']; // @phpstan-ignore-line
	}
}
