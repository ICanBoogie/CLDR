<?php

namespace ICanBoogie\CLDR\Locale;

/**
 * @see https://www.unicode.org/reports/tr35/tr35-72/tr35-general.html#ListPatterns
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
