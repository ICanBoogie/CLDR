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
use PHPUnit\Framework\TestCase;

class CodePropertyTraitTest extends TestCase
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
