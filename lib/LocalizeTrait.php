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
	 * Localizes the instance.
	 *
	 * @return mixed
	 */
	public function localize(string $locale_code)
	{
		$repository = $this->repository;

		assert($repository instanceof Repository);

		return $repository->locales[$locale_code]->localize($this);
	}
}
