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

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
	static private $repository;

	static public function setupBeforeClass()
	{
		self::$repository = get_repository();
	}

	/**
	 * @dataProvider provide_test_properties_type
	 */
	public function test_properties_type($property, $expected)
	{
		$this->assertInstanceOf($expected, self::$repository->$property);
	}

	public function provide_test_properties_type()
	{
		return array(

			array( 'currencies',   'ICanBoogie\CLDR\CurrencyCollection' ),
			array( 'locales',      'ICanBoogie\CLDR\LocaleCollection' ),
			array( 'provider',     'ICanBoogie\CLDR\Provider' ),
			array( 'supplemental', 'ICanBoogie\CLDR\Supplemental' ),
			array( 'territories',  'ICanBoogie\CLDR\TerritoryCollection' ),

		);
	}

	/**
	 * @expectedException \ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined_property()
	{
		self::$repository->undefined_property;
	}
}
