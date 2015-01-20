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
 * Class LocalizedListFormatter
 *
 * @package ICanBoogie\CLDR
 *
 * @property-read ListFormatter $target
 */
class LocalizedListFormatter extends LocalizedObject
{
	const TYPE_STANDARD = 'standard';
	const TYPE_UNIT = 'unit';
	const TYPE_UNIT_NARROW = 'unit-narrow';
	const TYPE_UNIT_SHORT = 'unit-short';

	/**
	 * Formats a variable-length lists of things.
	 *
	 * @param array $list The list to format.
	 * @param array|string $list_patterns_or_type A list patterns or a list patterns type (one
	 * of `TYPE_*`).
	 *
	 * @return string
	 */
	public function __invoke(array $list, $list_patterns_or_type=self::TYPE_STANDARD)
	{
		return $this->format($list, $list_patterns_or_type);
	}

	/**
	 * Formats a variable-length lists of things.
	 *
	 * @param array $list The list to format.
	 * @param array|string $list_patterns_or_type A list patterns or a list patterns type (one
	 * of TYPE_*).
	 *
	 * @return string
	 */
	public function format(array $list, $list_patterns_or_type=self::TYPE_STANDARD)
	{
		if (is_string($list_patterns_or_type))
		{
			$list_patterns_or_type = $this->locale['listPatterns']["listPattern-type-$list_patterns_or_type"];
		}

		return $this->target->format($list, $list_patterns_or_type);
	}
}
