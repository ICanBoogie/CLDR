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

		parent::__construct(function ($locale) {

			$this->assert_locale_is_valid($locale);
			$this->assert_locale_is_available($locale);

			return new Locale($this->repository, $locale);

		});
	}

	/**
	 * @param $locale
	 *
	 * @throws \InvalidArgumentException if the specified locale is not valid.
	 */
	private function assert_locale_is_valid($locale)
	{
		if (!$locale)
		{
			throw new \InvalidArgumentException("Locale identifier is empty");
		}
	}

	/**
	 * @param string $locale
	 *
	 * @throws \LogicException if the specified locale is not available.
	 */
	private function assert_locale_is_available($locale)
	{
		if ($this->repository->is_locale_available($locale))
		{
			return;
		}

		throw new \LogicException("Unavailable locale: $locale");
	}
}
