<?php

namespace ICanBoogie\CLDR;

/**
 *
 * @see: https://www.unicode.org/reports/tr35/tr35-66/tr35-dates.html#26-element-datetimeformats
 */
final class DateTimeFormatId
{
	public static function from(string $id): self
	{
		return new self($id);
	}

	private function __construct(
		public readonly string $id,
	) {
	}
}
