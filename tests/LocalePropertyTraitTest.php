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

use ICanBoogie\CLDR\LocalePropertyTraitTest\A;
use PHPUnit\Framework\TestCase;

final class LocalePropertyTraitTest extends TestCase
{
	public function test_get_repository(): void
	{
		$locale = get_repository()->locales['fr'];
		$a = new A($locale);
		$this->assertSame($locale, $a->locale);
	}
}
