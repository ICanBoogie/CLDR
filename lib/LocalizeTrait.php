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
 * @property-read Repository $repository
 */
trait LocalizeTrait
{
	/**
	 * Localize the instance.
	 *
	 * @param string $locale_code
	 *
	 * @return mixed
	 *
	 * @throw \LogicException when the instance was created without a repository.
	 */
	public function localize($locale_code)
	{
		$repository = $this->repository;

		if (!$repository)
		{
			throw new \LogicException("The instance was created without a repository.");
		}

		return $repository->locales[$locale_code]->localize($this);
	}
}
