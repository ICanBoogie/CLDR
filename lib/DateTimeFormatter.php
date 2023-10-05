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

use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

use function ceil;
use function floor;
use function is_array;
use function is_numeric;
use function str_pad;
use function str_repeat;
use function strlen;
use function substr;

use const STR_PAD_LEFT;

/**
 * Provides date and time localization.
 *
 * The class allows you to format dates and times in a locale-sensitive manner using
 * {@link https://www.unicode.org/reports/tr35/tr35-66/tr35-dates.html#Date_Format_Patterns Unicode format patterns}.
 *
 * @property-read Calendar $calendar The calendar used by the formatter.
 */
class DateTimeFormatter implements Formatter
{
	/**
	 * Pattern characters mapping to the corresponding translator methods.
	 *
	 * @var array<string, string>
	 *     Where _key_ is a pattern character and _value_ its formatter.
	 */
	private static array $formatters = [

		'G' => 'format_era',
		'y' => 'format_year',
//		'Y' => Year (in "Week of Year" based calendars).
//		'u' => Extended year.
		'Q' => 'format_quarter',
		'q' => 'format_standalone_quarter',
		'M' => 'format_month',
		'L' => 'format_standalone_month',
//		'l' => Special symbol for Chinese leap month, used in combination with M. Only used with the Chinese calendar.
		'w' => 'format_week_of_year',
		'W' => 'format_week_of_month',
		'd' => 'format_day_of_month',
		'D' => 'format_day_of_year',
		'F' => 'format_day_of_week_in_month',

		'h' => 'format_hour12',
		'H' => 'format_hour24',
		'm' => 'format_minutes',
		's' => 'format_seconds',
		'E' => 'format_day_in_week',
		'c' => 'format_day_in_week_stand_alone',
		'e' => 'format_day_in_week_local',
		'a' => 'format_period',
		'k' => 'format_hour_in_day',
		'K' => 'format_hour_in_period',
		'z' => 'format_timezone_non_location',
		'Z' => 'format_timezone_basic',
		'v' => 'format_timezone_non_location'

	];

	/**
	 * Parses the datetime format pattern.
	 *
	 * @return array<string|array{0: string, 1: int}>
	 *     Where _value_ is either a literal or an array where `0` is a formatter method and `1` a length.
	 */
	private static function tokenize(string $pattern): array
	{
		static $formats = [];

		if (isset($formats[$pattern])) {
			return $formats[$pattern];
		}

		$tokens = [];
		$is_literal = false;
		$literal = '';

		for ($i = 0, $n = strlen($pattern); $i < $n; ++$i) {
			$c = $pattern[$i];

			if ($c === "'") {
				if ($i < $n - 1 && $pattern[$i + 1] === "'") {
					$tokens[] = "'";
					$i++;
				} else {
					if ($is_literal) {
						$tokens[] = $literal;
						$literal = '';
						$is_literal = false;
					} else {
						$is_literal = true;
						$literal = '';
					}
				}
			} else {
				if ($is_literal) {
					$literal .= $c;
				} else {
					for ($j = $i + 1; $j < $n; ++$j) {
						if ($pattern[$j] !== $c) {
							break;
						}
					}

					$l = $j - $i;
					$p = str_repeat($c, $l);

					$tokens[] = isset(self::$formatters[$c]) ? [ self::$formatters[$c], $l ] : $p;

					$i = $j - 1;
				}
			}
		}

		if ($literal) {
			$tokens[] = $literal;
		}

		return $formats[$pattern] = $tokens;
	}

	/**
	 * Pad a numeric value with zero on its left.
	 */
	static private function numeric_pad(int $value, int $length = 2): string
	{
		return str_pad((string)$value, $length, '0', STR_PAD_LEFT);
	}

	public function __construct(
		public readonly Calendar $calendar
	) {
	}

	/**
	 * Alias to the {@link format()} method.
	 *
	 * @param DateTimeInterface|mixed $datetime
	 *
	 * @throws \Exception
	 */
	public function __invoke(
		$datetime,
		string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
	): string {
		return $this->format($datetime, $pattern_or_length_or_id);
	}

	/**
	 * Formats a date according to a pattern.
	 *
	 * @param DateTimeInterface|string|int $datetime
	 *     The datetime to format.
	 *
	 * @return string
	 *     The formatted date time.
	 *
	 * @throws \Exception
	 *
	 * @see https://www.unicode.org/reports/tr35/tr35-66/tr35-dates.html#26-element-datetimeformats
	 *
	 * @uses format_era
	 * @uses format_year
	 * @uses format_standalone_quarter
	 * @uses format_standalone_month
	 * @uses format_week_of_year
	 * @uses format_week_of_month
	 * @uses format_day_of_month
	 * @uses format_day_of_year
	 * @uses format_day_of_week_in_month
	 * @uses format_day_in_week
	 * @uses format_day_in_week_stand_alone
	 * @uses format_day_in_week_local
	 * @uses format_period
	 * @uses format_hour12
	 * @uses format_hour24
	 * @uses format_hour_in_period
	 * @uses format_hour_in_day
	 * @uses format_minutes
	 * @uses format_seconds
	 * @uses format_timezone_basic
	 * @uses format_timezone_non_location
	 *
	 */
	public function format(
		$datetime,
		string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
	): string {
		$datetime = $this->ensure_datetime($datetime);
		$datetime = new DateTimeAccessor($datetime);
		$pattern = $this->resolve_pattern($pattern_or_length_or_id);
		$tokens = self::tokenize($pattern);

		$rc = '';

		foreach ($tokens as $token) {
			if (is_array($token)) // a callback: method name, repeating chars
			{
				$token = $this->{$token[0]}($datetime, $token[1]);
			}

			$rc .= $token;
		}

		return $rc;
	}

	/**
	 * Resolves the specified pattern, which can be a width, a skeleton or an actual pattern.
	 */
	protected function resolve_pattern(
		string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
	): string {
		if (is_string($pattern_or_length_or_id) && $pattern_or_length_or_id[0] === ':') {
			trigger_error(
				"Prefixing date time format ids with ':' is no longer supported, use DateTimeFormatId instead",
				E_USER_DEPRECATED
			);

			$pattern_or_length_or_id = DateTimeFormatId::from(substr($pattern_or_length_or_id, 1));
		}

		if ($pattern_or_length_or_id instanceof DateTimeFormatLength) {
			$length = $pattern_or_length_or_id->value;
			$calendar = $this->calendar;
			$datetime_pattern = $calendar['dateTimeFormats'][$length];
			$date_pattern = $calendar['dateFormats'][$length];
			$time_pattern = $calendar['timeFormats'][$length];

			return strtr($datetime_pattern, [
				'{1}' => $date_pattern,
				'{0}' => $time_pattern
			]);
		} elseif ($pattern_or_length_or_id instanceof DateTimeFormatId) {
			$id = $pattern_or_length_or_id->id;

			return $this->calendar['dateTimeFormats']['availableFormats'][$id]
				?? throw new RuntimeException("Unknown DateTime format id: $id");
		}

		return $pattern_or_length_or_id;
	}

	/*
	 * era (G)
	 */

	/**
	 * Era - Replaced with the Era string for the current date. One to three letters for the
	 * abbreviated form, four letters for the long form, five for the narrow form. [1..3,4,5]
	 * @todo How to support multiple Eras?, e.g. Japanese.
	 */
	private function format_era(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 5) {
			return '';
		}

		$era = ($datetime->year > 0) ? 1 : 0;

		return match ($length) {
			1, 2, 3 => $this->calendar->abbreviated_eras[$era],
			4 => $this->calendar->wide_eras[$era],
			5 => $this->calendar->narrow_eras[$era],
			default => '',
		};
	}

	/*
	 * year (y)
	 */

	/**
	 * Year. Normally the length specifies the padding, but for two letters it also specifies the
	 * maximum length. [1..n]
	 */
	private function format_year(DateTimeAccessor $datetime, int $length): string
	{
		$year = $datetime->year;

		if ($length == 2) {
			$year = $year % 100;
		}

		return self::numeric_pad($year, $length);
	}

	/*
	 * quarter (Q,q)
	 */

	/**
	 * Quarter - Use one or two "Q" for the numerical quarter, three for the abbreviation, or four
	 * for the full (wide) name. [1..2,3,4]
	 *
	 * @uses Calendar::$abbreviated_quarters
	 * @uses Calendar::$wide_quarters
	 */
	private function format_quarter(
		DateTimeAccessor $datetime,
		int $length,
		string $abbreviated = 'abbreviated_quarters',
		string $wide = 'wide_quarters'
	): string {
		if ($length > 4) {
			return '';
		}

		$quarter = $datetime->quarter;

		return match ($length) {
			1 => (string)$quarter,
			2 => self::numeric_pad($quarter),
			3 => $this->calendar->$abbreviated[$quarter],
			4 => $this->calendar->$wide[$quarter],
			default => '',
		};
	}

	/**
	 * Stand-Alone Quarter - Use one or two "q" for the numerical quarter, three for the
	 * abbreviation, or four for the full (wide) name. [1..2,3,4]
	 *
	 * @uses Calendar::$standalone_abbreviated_quarters
	 * @uses Calendar::$standalone_wide_quarters
	 */
	private function format_standalone_quarter(DateTimeAccessor $datetime, int $length): string
	{
		return $this->format_quarter(
			datetime: $datetime,
			length: $length,
			abbreviated: 'standalone_abbreviated_quarters',
			wide: 'standalone_wide_quarters',
		);
	}

	/*
	 * month (M|L)
	 */

	/**
	 * Month - Use one or two "M" for the numerical month, three for the abbreviation, four for
	 * the full name, or five for the narrow name. [1..2,3,4,5]
	 *
	 * @uses Calendar::$abbreviated_months
	 * @uses Calendar::$wide_months
	 * @uses Calendar::$narrow_months
	 */
	private function format_month(
		DateTimeAccessor $datetime,
		int $length,
		string $abbreviated = 'abbreviated_months',
		string $wide = 'wide_months',
		string $narrow = 'narrow_months'
	): string {
		if ($length > 5) {
			return '';
		}

		$month = $datetime->month;

		switch ($length) {
			case 1:
				return (string)$month;
			case 2:
				return self::numeric_pad($month);
			case 3:
				$names = $this->calendar->$abbreviated;
				return $names[$month];
			case 4:
				$names = $this->calendar->$wide;
				return $names[$month];
			case 5:
				$names = $this->calendar->$narrow;
				return $names[$month];
		}

		return ''; // @codeCoverageIgnore
	}

	/**
	 * Stand-Alone Month - Use one or two "L" for the numerical month, three for the abbreviation,
	 * or four for the full (wide) name, or 5 for the narrow name. [1..2,3,4,5]
	 *
	 * @uses Calendar::$standalone_abbreviated_months
	 * @uses Calendar::$standalone_wide_months
	 * @uses Calendar::$standalone_narrow_months
	 */
	private function format_standalone_month(DateTimeAccessor $datetime, int $length): string
	{
		return $this->format_month(
			datetime: $datetime,
			length: $length,
			abbreviated: 'standalone_abbreviated_months',
			wide: 'standalone_wide_months',
			narrow: 'standalone_narrow_months'
		);
	}

	/*
	 * week (w|W)
	 */

	/**
	 * Week of Year. [1..2]
	 */
	private function format_week_of_year(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$week = $datetime->week;

		return $length == 1 ? (string)$week : self::numeric_pad($week);
	}

	/**
	 * Week of Month. [1]
	 */
	private function format_week_of_month(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 1) {
			return '';
		}

		return (string)ceil($datetime->day / 7) ?: "0";
	}

	/*
	 * day (d,D,F)
	 */

	/**
	 * Date - Day of the month. [1..2]
	 */
	private function format_day_of_month(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$day = $datetime->day;

		if ($length == 1) {
			return (string)$day;
		}

		return self::numeric_pad($day);
	}

	/**
	 * Day of year. [1..3]
	 */
	private function format_day_of_year(DateTimeAccessor $datetime, int $length): string
	{
		$day = $datetime->year_day;

		if ($length > 3) {
			return '';
		}

		return self::numeric_pad($day, $length);
	}

	/**
	 * Day of Week in Month. The example is for the 2nd Wed in July. [1]
	 */
	private function format_day_of_week_in_month(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 1) {
			return '';
		}

		return (string)floor(($datetime->day + 6) / 7);
	}

	/*
	 * weekday (E,e,c)
	 */

	/**
	 * Day of week - Use one through three letters for the short day, or four for the full name,
	 * five for the narrow name, or six for the short name. [1..3,4,5,6]
	 */
	private function format_day_in_week(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 6) {
			return '';
		}

		$day = $datetime->weekday;
		$code = $this->resolve_day_code($day);
		$calendar = $this->calendar;

		return match ($length) {
			1, 2, 3 => $calendar->abbreviated_days[$code],
			4 => $calendar->wide_days[$code],
			5 => $calendar->narrow_days[$code],
			6 => $calendar->short_days[$code],
			default => '',
		};
		// @codeCoverageIgnore
	}

	/**
	 * Stand-Alone local day of week - Use one letter for the local numeric value (same as 'e'),
	 * three for the abbreviated day name, four for the full (wide) name, five for the narrow name,
	 * or six for the short name.
	 *
	 * @uses Calendar::$standalone_abbreviated_days
	 * @uses Calendar::$standalone_wide_days
	 * @uses Calendar::$standalone_narrow_days
	 * @uses Calendar::$standalone_short_days
	 */
	private function format_day_in_week_stand_alone(DateTimeAccessor $datetime, int $length): string
	{
		static $mapping = [

			3 => 'abbreviated',
			4 => 'wide',
			5 => 'narrow',
			6 => 'short',

		];

		if ($length == 2 || $length > 6) {
			return '';
		}

		$day = $datetime->weekday;

		if ($length == 1) {
			return (string)$day;
		}

		$code = $this->resolve_day_code($day);

		return $this->calendar->{'standalone_' . $mapping[$length] . '_days'}[$code];
	}

	/**
	 * Local day of week. Same as E except adds a numeric value that will depend on the local
	 * starting day of the week, using one or two letters. For this example, Monday is the
	 * first day of the week.
	 */
	private function format_day_in_week_local(DateTimeAccessor $datetime, int $length): string
	{
		if ($length < 3) {
			return (string)$datetime->weekday;
		}

		return $this->format_day_in_week($datetime, $length);
	}

	/*
	 * period (a)
	 */

	/**
	 * AM or PM. [1]
	 *
	 * @return string AM or PM designator
	 */
	private function format_period(DateTimeAccessor $datetime): string
	{
		return $this->calendar['dayPeriods']['format']['abbreviated'][$datetime->hour < 12 ? 'am' : 'pm'];
	}

	/*
	 * hour (h,H,K,k)
	 */

	/**
	 * Hour [1-12]. When used in skeleton data or in a skeleton passed in an API for flexible data
	 * pattern generation, it should match the 12-hour-cycle format preferred by the locale
	 * (h or K); it should not match a 24-hour-cycle format (H or k). Use hh for zero
	 * padding. [1..2]
	 */
	private function format_hour12(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$hour = $datetime->hour;
		$hour = ($hour == 12) ? 12 : $hour % 12;

		if ($length == 1) {
			return (string)$hour;
		}

		return self::numeric_pad($hour);
	}

	/**
	 * Hour [0-23]. When used in skeleton data or in a skeleton passed in an API for flexible
	 * data pattern generation, it should match the 24-hour-cycle format preferred by the
	 * locale (H or k); it should not match a 12-hour-cycle format (h or K). Use HH for zero
	 * padding. [1..2]
	 */
	private function format_hour24(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$hour = $datetime->hour;

		if ($length == 1) {
			return (string)$hour;
		}

		return self::numeric_pad($hour);
	}

	/**
	 * Hour [0-11]. When used in a skeleton, only matches K or h, see above. Use KK for zero
	 * padding. [1..2]
	 */
	private function format_hour_in_period(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$hour = $datetime->hour % 12;

		if ($length == 1) {
			return (string)$hour;
		}

		return self::numeric_pad($hour);
	}

	/**
	 * Hour [1-24]. When used in a skeleton, only matches k or H, see above. Use kk for zero
	 * padding. [1..2]
	 */
	private function format_hour_in_day(DateTimeAccessor $datetime, int $length): string
	{
		if ($length > 2) {
			return '';
		}

		$hour = $datetime->hour ?: 24;

		if ($length == 1) {
			return (string)$hour;
		}

		return self::numeric_pad($hour);
	}

	/*
	 * minute (m)
	 */

	/**
	 * Minute. Use one or two "m" for zero padding.
	 */
	private function format_minutes(DateTimeAccessor $datetime, int $length): string
	{
		return $this->format_minutes_or_seconds($datetime, $length, 'minute');
	}

	/*
	 * second
	 */

	/**
	 * Second. Use one or two "s" for zero padding.
	 */
	private function format_seconds(DateTimeAccessor $datetime, int $length): string
	{
		return $this->format_minutes_or_seconds($datetime, $length, 'second');
	}

	/**
	 * Minute. Use one or two "m" for zero padding.
	 */
	private function format_minutes_or_seconds(DateTimeAccessor $datetime, int $length, string $which): string
	{
		if ($length > 2) {
			return '';
		}

		$value = $datetime->$which;

		if ($length == 1) {
			return $value;
		}

		return self::numeric_pad($value);
	}

	/*
	 * zone (z,Z,v)
	 */

	/**
	 * The ISO8601 basic format.
	 */
	private function format_timezone_basic(DateTimeAccessor $datetime): string
	{
		return $datetime->format('O');
	}

	/**
	 * The specific non-location format.
	 */
	private function format_timezone_non_location(DateTimeAccessor $datetime): string
	{
		$str = $datetime->format('T');

		return $str === 'Z' ? 'UTC' : $str;
	}

	/**
	 * @param DateTimeInterface|string|int $datetime
	 *
	 * @throws \Exception
	 */
	private function ensure_datetime($datetime): DateTimeInterface
	{
		if ($datetime instanceof DateTimeInterface) {
			return $datetime;
		}

		return new DateTimeImmutable(is_numeric($datetime) ? "@$datetime" : (string)$datetime);
	}

	private function resolve_day_code(int $day): string
	{
		static $translate = [

			1 => 'mon',
			2 => 'tue',
			3 => 'wed',
			4 => 'thu',
			5 => 'fri',
			6 => 'sat',
			7 => 'sun'

		];

		return $translate[$day];
	}
}
