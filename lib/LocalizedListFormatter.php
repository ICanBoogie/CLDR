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
 * @extends LocalizedObject<ListFormatter>
 *
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#ListPatterns
 */
class LocalizedListFormatter extends LocalizedObject implements Formatter
{
	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 */
	public function __invoke(array $list, ListType $type = ListType::STANDARD): string
	{
		return $this->format($list, $type);
	}

	/**
	 * Formats a variable-length lists of scalars.
	 *
	 * @param scalar[] $list
	 */
	public function format(array $list, ListType $type = ListType::STANDARD): string
	{
		/** @phpstan-ignore-next-line */
		$list_pattern = ListPattern::from($this->locale['listPatterns']["listPattern-type-$type->value"]);

		return $this->target->format($list, $list_pattern);
	}
}
