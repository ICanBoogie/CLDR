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

class LocalizedCurrencyTest extends \PHPUnit_Framework_TestCase
{
	static private $currency;
	static private $localized;

	static public function setUpBeforeClass()
	{
		self::$currency = new Currency(get_repository(), 'IEP');
		self::$localized = new LocalizedCurrency(self::$currency, get_repository()->locales['fr']);
	}

	public function test_get_name()
	{
		$this->assertEquals("livre irlandaise", self::$localized->name);
		$this->assertEquals("livre irlandaise", self::$localized->get_name(1));
		$this->assertEquals("livres irlandaises", self::$localized->get_name(10));
	}

	public function test_get_symbol()
	{
		$this->assertEquals("£IE", self::$localized->symbol);
	}

	public function test_localize()
	{
		$localized = self::$currency->localize('en-US');
		$this->assertInstanceOf('ICanBoogie\CLDR\LocalizedCurrency', $localized);
		$this->assertEquals("Irish Pound", $localized->name);
	}
}
