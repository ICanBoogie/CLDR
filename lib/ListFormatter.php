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
 * Formats variable-length lists of things such as "Monday, Tuesday, Friday, and Saturday".
 *
 * @package ICanBoogie\CLDR
 *
 * @see http://www.unicode.org/reports/tr35/tr35-general.html#ListPatterns
 */
class ListFormatter
{
	use AccessorTrait;
	use RepositoryPropertyTrait;

	/**
	 * @param Repository $repository
	 */
	public function __construct(Repository $repository=null)
	{
		$this->repository = $repository;
	}

	/**
	 * Formats a variable-length lists of things.
	 *
	 * @param array $list The list to format.
	 * @param array $list_patterns A list patterns.
	 *
	 * @return string
	 */
	public function __invoke(array $list, array $list_patterns)
	{
		return $this->format($list, $list_patterns);
	}

	/**
	 * Formats a variable-length lists of things.
	 *
	 * @param array $list The list to format.
	 * @param array $list_patterns A list patterns.
	 *
	 * @return string
	 */
	public function format(array $list, array $list_patterns)
	{
		$list = array_values($list);

		switch (count($list))
		{
			case 0:

				return "";

			case 1:

				return (string) current($list);

			case 2:

				return $this->format_pattern($list_patterns[2], $list[0], $list[1]);

			default:

				$n = count($list) - 1;
				$v1 = $list[$n];

				for ($i = $n - 1 ; $i > -1 ; $i--)
				{
					$v0 = $list[$i];

					if ($i == 0)
					{
						$pattern = $list_patterns['start'];
					}
					else if ($i + 1 == $n)
					{
						$pattern = $list_patterns['end'];
					}
					else
					{
						$pattern = $list_patterns['middle'];
					}

					$v1 = $this->format_pattern($pattern, $v0, $v1);
				}

				return $v1;
		}
	}

	private function format_pattern($pattern, $v0, $v1)
	{
		return strtr($pattern, [

			'{0}' => $v0,
			'{1}' => $v1

		]);
	}

	/**
	 * Localize the instance.
	 *
	 * @param $locale_code
	 *
	 * @return LocalizedListFormatter
	 *
	 * @throw \LogicException when the instance was created without a repository.
	 */
	public function localize($locale_code)
	{
		if (!$this->repository)
		{
			throw new \LogicException("The instance was created without a repository.");
		}

		return $this->repository->locales[$locale_code]->localize($this);
	}
}
