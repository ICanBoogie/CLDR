<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\CLDR\Locale;

/**
 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#ListPatterns
 */
final class ListPattern
{
	/**
	 * @param array{
	 *     2: string,
	 *     start: string,
	 *     middle: string,
	 *     end: string,
	 * } $list_pattern
	 */
	static public function from(array $list_pattern): self
	{
		return new self(
			$list_pattern['2'],
			$list_pattern['start'],
			$list_pattern['middle'],
			$list_pattern['end']
		);
	}

	private function __construct(
		public readonly string $two,
		public readonly string $start,
		public readonly string $middle,
		public readonly string $end
	) {
	}
}
