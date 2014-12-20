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

use ICanBoogie\CLDR\AccessorTraitTest\A;

class AccessorTraitTest extends \PHPUnit_Framework_TestCase
{
	public function get_getter()
	{
		$a = new A;
		$random = $a->random;
		$this->assertNotEmpty($random);
		$this->asserNotSame($a, $a->random);
		$this->asserNotSame($a, $a->random);
		$this->asserNotSame($a, $a->random);
	}

	public function get_lazy_getter()
	{
		$a = new A;
		$random = $a->random;
		$this->assertNotEmpty($random);
		$this->asserSame($a, $a->random);
		$this->asserSame($a, $a->random);
		$this->asserSame($a, $a->random);

		unset($a->random);
		$this->asserNotSame($a, $a->random);
	}

	/**
	 * @expectedException \ICanBoogie\PropertyNotDefined
	 */
	public function test_get_undefined_property()
	{
		$a = new A;
		$a->undefined_property;
	}
}

namespace ICanBoogie\CLDR\AccessorTraitTest;

use ICanBoogie\CLDR\AccessorTrait;

class A
{
	use AccessorTrait;

	protected function get_random()
	{
		return mt_rand();
	}

	protected function lazy_get_pseudo_random()
	{
		return mt_rand();
	}
}
