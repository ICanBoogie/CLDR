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

use ICanBoogie\Accessor\AccessorTrait;

/**
 * Representation of a locale collection.
 *
 * @method Locale offsetGet($id)
 */
class LocaleCollection extends AbstractCollection
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function ($id) {

			return new Locale($this->repository, $id);

		});
	}
}
