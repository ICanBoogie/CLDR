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

use ICanBoogie\CLDR\CodePropertyTraitTest\A;

class CodePropertyTraitTest extends \PHPUnit_Framework_TestCase
{
	public function test_get_code()
	{
		$expected = 'fr';
		$a = new A($expected);
		$this->assertSame($expected, $a->code);
	}

	public function test_to_string()
	{
		$expected = 'fr';
		$a = new A($expected);
		$this->assertSame($expected, (string) $a);
	}
}

namespace ICanBoogie\CLDR\CodePropertyTraitTest;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\CodePropertyTrait;

class A
{
	use AccessorTrait;
	use CodePropertyTrait;

	public function __construct($code)
	{
		$this->code = $code;
	}
}
