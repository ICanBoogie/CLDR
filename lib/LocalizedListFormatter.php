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

use ICanBoogie\CLDR\Locale\ListPattern;

/**
 * Formats a variable-length lists of things.
 *
 * @property-read ListFormatter $target
 */
class LocalizedListFormatter extends LocalizedObject implements Formatter
{
	public const TYPE_STANDARD = 'standard';
	public const TYPE_UNIT = 'unit';
	public const TYPE_UNIT_NARROW = 'unit-narrow';
	public const TYPE_UNIT_SHORT = 'unit-short';

	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 * @param self::TYPE_* $type
	 */
	public function __invoke(array $list, string $type = self::TYPE_STANDARD): string
	{
		return $this->format($list, $type);
	}

	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 * @param self::TYPE_* $type
	 */
	public function format(array $list, string $type = self::TYPE_STANDARD): string
	{
		$list_pattern = ListPattern::from($this->locale['listPatterns']["listPattern-type-$type"]);

		return $this->target->format($list, $list_pattern);
	}
}
