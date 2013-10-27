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

	public function test_get_provider()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Provider', self::$repository->provider);
	}

	public function test_get_locales()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\LocaleCollection', self::$repository->locales);
	}

	public function test_get_supplemental()
	{
		$this->assertInstanceOf('ICanBoogie\CLDR\Supplemental', self::$repository->supplemental);
	}

	/**
	 * @expectedException ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined_property()
	{
		self::$repository->undefined_property;
	}
}