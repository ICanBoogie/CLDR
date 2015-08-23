<?php

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
