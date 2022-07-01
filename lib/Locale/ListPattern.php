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
 * @see https://www.unicode.org/reports/tr35/tr35-57/tr35-general.html#ListPatterns (v36)
 */
final class ListPattern
{
	/**
	 * @var string
	 * @readonly
	 */
	public $two;

	/**
	 * @var string
	 * @readonly
	 */
	public $start;

	/**
	 * @var string
	 * @readonly
	 */
	public $middle;

	/**
	 * @var string
	 * @readonly
	 */
	public $end;

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
		string $two,
		string $start,
		string $middle,
		string $end
	) {
		$this->two = $two;
		$this->start = $start;
		$this->middle = $middle;
		$this->end = $end;
	}
}
