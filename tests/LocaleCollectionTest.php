<?php

namespace Test\ICanBoogie\CLDR;

use BadMethodCallException;
use ICanBoogie\CLDR\Locale;
use ICanBoogie\CLDR\LocaleCollection;
use ICanBoogie\CLDR\LocaleNotAvailable;
use ICanBoogie\OffsetNotWritable;
use PHPUnit\Framework\TestCase;

final class LocaleCollectionTest extends TestCase
{
	private static LocaleCollection $sut;

	public static function setupBeforeClass(): void
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
	public function should_fail_with_undefined_locale(): void
	{
		$this->expectException(LocaleNotAvailable::class);
		self::$sut['madonna']; // @phpstan-ignore-line
	}
}
