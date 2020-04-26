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

/**
 * A trait for classes implementing the `repository` property.
 *
 * @property-read Repository $repository
 */
trait RepositoryPropertyTrait
{
	/**
	 * @var Repository
	 */
	private $repository;

	private function get_repository(): Repository
	{
		return $this->repository;
	}
}
