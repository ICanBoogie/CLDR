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
use InvalidArgumentException;

/**
 * Representation of a locale collection.
 *
 * @extends AbstractCollection<Locale>
 */
class LocaleCollection extends AbstractCollection
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	public function __construct(Repository $repository)
	{
		$this->repository = $repository;

		parent::__construct(function (string $code): Locale {

			$this->assert_locale_is_valid($code);
			$this->assert_locale_is_available($code);

			return new Locale($this->repository, $code);

		});
	}

	/**
	 * @throws InvalidArgumentException if the specified locale is not valid.
	 */
	private function assert_locale_is_valid(string $code): void
	{
		if (!$code)
		{
			throw new InvalidArgumentException("Locale code should not be empty.");
		}
	}

	/**
	 * @throws InvalidArgumentException if the specified locale is not available.
	 */
	private function assert_locale_is_available(string $code): void
	{
		if ($this->repository->is_locale_available($code))
		{
			return;
		}

		throw new InvalidArgumentException("Locale is not available: $code.");
	}
}
