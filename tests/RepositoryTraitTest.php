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

class RepositoryPropertyTraitTest extends \PHPUnit_Framework_TestCase
{
	public function test_get_repository()
	{
		$repository = get_repository();
		$a = new A($repository);
		$this->assertSame($repository, $a->repository);
	}
}

namespace ICanBoogie\CLDR\RepositoryPropertyTraitTest;

use ICanBoogie\Accessor\AccessorTrait;
use ICanBoogie\CLDR\Repository;
use ICanBoogie\CLDR\RepositoryPropertyTrait;

class A
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	public function __construct(Repository $repository)
	{
		$this->repository = $repository;
	}
}
