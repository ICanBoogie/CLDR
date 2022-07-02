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

use ICanBoogie\CLDR\RepositoryPropertyTraitTest\A;
use PHPUnit\Framework\TestCase;

final class RepositoryPropertyTraitTest extends TestCase
{
	public function test_get_repository(): void
	{
		$repository = get_repository();
		$a = new A($repository);
		$this->assertSame($repository, $a->repository);
	}
}
