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

use ICanBoogie\DateTime;

/**
 * Provides date and time localization.
 *
 * The class allows you to format dates and times in a locale-sensitive manner using
 * {@link http://www.unicode.org/reports/tr35/#Date_Format_Patterns Unicode format patterns}.
 *
 * @property-read Calendar $calendar The calendar used by the formatter.
 */
class DateTimeFormatter
{
	use AccessorTrait;

	/**
	 * Pattern characters mapping to the corresponding translator methods.
	 *
	 * @var array
	 */
	static protected $formatters = [

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
		'c' => 'format_day_in_week',
		'e' => 'format_day_in_week',
		'a' => 'format_period',
		'k' => 'format_hour_in_day',
		'K' => 'format_hour_in_period',
		'z' => 'format_timezone',
		'Z' => 'format_timezone',
		'v' => 'format_timezone'

	];

	/**
	 * Parses the datetime format pattern.
	 *
	 * @param string $pattern the pattern to be parsed
	 *
	 * @return array tokenized parsing result
	 */
	static protected function tokenize($pattern)
	{
		static $formats = [];

		if (isset($formats[$pattern]))
		{
			return $formats[$pattern];
		}

		$tokens = [];
		$is_literal = false;
		$literal = '';

		for ($i = 0, $n = strlen($pattern) ; $i < $n ; ++$i)
		{
			$c = $pattern{$i};

			if ($c === "'")
			{
				if ($i < $n-1 && $pattern{$i+1} === "'")
				{
					$tokens[] = "'";
					$i++;
				}
				else if ($is_literal)
				{
					$tokens[] = $literal;
					$literal = '';
					$is_literal = false;
				}
				else
				{
					$is_literal = true;
					$literal = '';
				}
			}
			else if ($is_literal)
			{
				$literal .= $c;
			}
			else
			{
				for ($j = $i + 1 ; $j < $n ; ++$j)
				{
					if ($pattern{$j} !== $c) break;
				}

				$l = $j-$i;
				$p = str_repeat($c, $l);

				$tokens[] = isset(self::$formatters[$c]) ? [ self::$formatters[$c], $p, $l ] : $p;

				$i = $j - 1;
			}
		}

		if ($literal)
		{
			$tokens[] = $literal;
		}

		return $formats[$pattern] = $tokens;
	}

	/**
	 * The calendar used to format the datetime.
	 *
	 * @var Calendar
	 */
	protected $calendar;

	protected function get_calendar()
	{
		return $this->calendar;
	}

	/**
	 * Initializes the {@link $calendar} property.
	 *
	 * @param Calendar $calendar
	 */
	public function __construct(Calendar $calendar)
	{
		$this->calendar = $calendar;
	}

	/**
	 * Alias to the {@link format()} method.
	 *
	 * @param mixed $datetime
	 * @param string $pattern_or_width_or_skeleton
	 *
	 * @return string
	 */
	public function __invoke($datetime, $pattern_or_width_or_skeleton)
	{
		return $this->format($datetime, $pattern_or_width_or_skeleton);
	}

	/**
	 * Formats a date according to a pattern.
	 *
	 * <pre>
	 * <?php
	 *
	 * $datetime_formatter = $repository->locales['en']->calendar->datetime_formatter;
	 * $datetime = '2013-11-02 22:23:45';
	 *
	 * echo $datetime_formatter($datetime, "MMM d, y");                 // November 2, 2013 at 10:23:45 PM
	 * echo $datetime_formatter($datetime, "MMM d, y 'at' hh:mm:ss a"); // November 2, 2013 at 10:23:45 PM
	 * echo $datetime_formatter($datetime, 'full');                     // Saturday, November 2, 2013 at 10:23:45 PM CET
	 * echo $datetime_formatter($datetime, 'long');                     // November 2, 2013 at 10:23:45 PM CET
	 * echo $datetime_formatter($datetime, 'medium');                   // Nov 2, 2013, 10:23:45 PM
	 * echo $datetime_formatter($datetime, 'short');                    // 11/2/13, 10:23 PM
	 * echo $datetime_formatter($datetime, ':Ehm');                     // Sat 10:23 PM
	 * </pre>
	 *
	 * @param \DateTime|string|int $datetime The datetime to format.
	 * @param string $pattern_or_width_or_skeleton The datetime can be formatted using a pattern,
	 * a width or a skeleton. The following width are defined: "full", "long", "medium" and "short".
	 * To format the datetime using a so-called "skeleton", the skeleton identifier must be
	 * prefixed with the colon sign ":" e.g. ":Ehm". The skeleton identifies the patterns defined
	 * under `availableFormats`.
	 *
	 * @return string The formatted date time.
	 *
	 * @see http://www.unicode.org/reports/tr35/#Date_Format_Patterns
	 */
	public function format($datetime, $pattern_or_width_or_skeleton)
	{
		$datetime = DateTime::from(is_numeric($datetime) ? "@$datetime" : $datetime);
		$pattern = $this->resolve_pattern($pattern_or_width_or_skeleton);
		$tokens = self::tokenize($pattern);

		$rc = '';

		foreach ($tokens as $token)
		{
			if (is_array($token)) // a callback: method name, sub-pattern
			{
				$token = $this->{$token[0]}($datetime, $token[1], $token[2]);
			}

			$rc .= $token;
		}

		return $rc;
	}

	/**
	 * Resolves the specified pattern, which can be a width, a skeleton or an actual pattern.
	 *
	 * @param string $pattern_or_width_or_skeleton
	 *
	 * @return string
	 */
	protected function resolve_pattern($pattern_or_width_or_skeleton)
	{
		$pattern = $pattern_or_width_or_skeleton;

		if ($pattern_or_width_or_skeleton{0} === ':')
		{
			$skeleton = substr($pattern, 1);
			$available_formats = $this->calendar['dateTimeFormats']['availableFormats'];

			if (isset($available_formats[$skeleton]))
			{
				$pattern = $available_formats[$skeleton];
			}
		}
		else if (in_array($pattern = $pattern_or_width_or_skeleton, [ 'full', 'long', 'medium', 'short' ]))
		{
			$calendar = $this->calendar;
			$width = $pattern_or_width_or_skeleton;
			$datetime_pattern = $calendar['dateTimeFormats'][$width];
			$date_pattern = $calendar['dateFormats'][$width];
			$time_pattern = $calendar['timeFormats'][$width];
			$pattern = strtr($datetime_pattern, [ '{1}' => $date_pattern, '{0}' => $time_pattern ]);
		}

		return $pattern;
	}

	/**
	 * Resolves widths (full, long, medium, short) into a pattern.
	 *
	 * @param string $pattern_or_width_or_skeleton
	 * @param string $from Width Source e.g. "timeFormats".
	 *
	 * @return string
	 */
	protected function resolve_width($pattern_or_width_or_skeleton, $from)
	{
		static $widths = [ 'full', 'long', 'medium', 'short' ];

		if (in_array($pattern_or_width_or_skeleton, $widths))
		{
			return $this->calendar[$from][$pattern_or_width_or_skeleton];
		}

		return $pattern_or_width_or_skeleton;
	}

	/*
	 * era (G)
	 */

	/**
	 * Era - Replaced with the Era string for the current date. One to three letters for the
	 * abbreviated form, four letters for the long form, five for the narrow form. [1..3,4,5]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string era
	 * @todo How to support multiple Eras?, e.g. Japanese.
	 */
	protected function format_era(DateTime $datetime, $pattern, $length)
	{
		$era = ($datetime->year > 0) ? 1 : 0;

		switch($length)
		{
			case 1:
			case 2:
			case 3: return $this->calendar->abbreviated_eras[$era];
			case 4: return $this->calendar->wide_eras[$era];
			case 5: return $this->calendar->narrow_eras[$era];
		}
	}

	/*
	 * year (y)
	 */

	/**
	 * Year. Normally the length specifies the padding, but for two letters it also specifies the
	 * maximum length. [1..n]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string formatted year
	 */
	protected function format_year(Datetime $datetime, $pattern, $length)
	{
		$year = $datetime->year;

		if ($length == 2)
		{
			$year = $year % 100;
		}

		return str_pad($year, $length, '0', STR_PAD_LEFT);
	}

	/*
	 * quarter (Q,q)
	 */

	/**
	 * Quarter - Use one or two "Q" for the numerical quarter, three for the abbreviation, or four
	 * for the full (wide) name. [1..2,3,4]
	 *
	 * @param DateTime $datetime Datetime.
	 * @param string $pattern Pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_quarter(DateTime $datetime, $pattern, $length)
	{
		$quarter = $datetime->quarter;

		switch ($length)
		{
			case 1: return $quarter;
			case 2: return str_pad($quarter, 2, '0', STR_PAD_LEFT);
			case 3: return $this->calendar->abbreviated_quarters[$quarter];
			case 4: return $this->calendar->wide_quarters[$quarter];
		}
	}

	/**
	 * Stand-Alone Quarter - Use one or two "q" for the numerical quarter, three for the
	 * abbreviation, or four for the full (wide) name. [1..2,3,4]
	 *
	 * @param DateTime $datetime Datetime.
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_standalone_quarter(DateTime $datetime, $pattern, $length)
	{
		$quarter = $datetime->quarter;

		switch ($length)
		{
			case 1: return $quarter;
			case 2: return str_pad($quarter, 2, '0', STR_PAD_LEFT);
			case 3: return $this->calendar->standalone_abbreviated_quarters[$quarter];
			case 4: return $this->calendar->standalone_wide_quarters[$quarter];
		}
	}

	/*
	 * month (M|L)
	 */

	/**
	 * Month - Use one or two "M" for the numerical month, three for the abbreviation, four for
	 * the full name, or five for the narrow name. [1..2,3,4,5]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_month(DateTime $datetime, $pattern, $length)
	{
		$month = $datetime->month;

		switch ($length)
		{
			case 1: return $month;
			case 2: return str_pad($month, 2, '0', STR_PAD_LEFT);
			case 3: return $this->calendar->abbreviated_months[$month];
			case 4: return $this->calendar->wide_months[$month];
			case 5: return $this->calendar->narrow_months[$month];
		}
	}

	/**
	 * Stand-Alone Month - Use one or two "L" for the numerical month, three for the abbreviation,
	 * or four for the full (wide) name, or 5 for the narrow name. [1..2,3,4,5]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string formatted month.
	 */
	protected function format_standalone_month(DateTime $datetime, $pattern, $length)
	{
		$month = $datetime->month;

		switch ($length)
		{
			case 1: return $month;
			case 2: return str_pad($month, 2, '0', STR_PAD_LEFT);
			case 3: return $this->calendar->standalone_abbreviated_months[$month];
			case 4: return $this->calendar->standalone_wide_months[$month];
			case 5: return $this->calendar->standalone_narrow_months[$month];
		}
	}

	/*
	 * week (w|W)
	 */

	/**
	 * Week of Year. [1..2]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return integer
	 */
	protected function format_week_of_year(DateTime $datetime, $pattern, $length)
	{
		if ($length > 2)
		{
			return;
		}

		$week = $datetime->week;

		return $length == 1 ? $week : str_pad($week, 2, '0', STR_PAD_LEFT);
	}

	/**
	 * Week of Month. [1]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return int|false Week of month, of `false` if `$length` is greater than 1.
	 */
	protected function format_week_of_month(DateTime $datetime, $pattern, $length)
	{
		if ($length == 1)
		{
			return ceil($datetime->day / 7);
		}

		return false;
	}

	/*
	 * day (d,D,F)
	 */

	/**
	 * Date - Day of the month. [1..2]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string day of the month
	 */
	protected function format_day_of_month(DateTime $datetime, $pattern, $length)
	{
		$day = $datetime->day;

		if ($length == 1)
		{
			return $day;
		}
		else if ($length == 2)
		{
			return str_pad($day, 2, '0', STR_PAD_LEFT);
		}
	}

	/**
	 * Day of year. [1..3]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_day_of_year(DateTime $datetime, $pattern, $length)
	{
		$day = $datetime->year_day;

		if ($length > 3)
		{
			return;
		}

		return str_pad($day, $length, '0', STR_PAD_LEFT);
	}

	/**
	 * Day of Week in Month. The example is for the 2nd Wed in July. [1]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return int|false Day of week in mounth, or `false` if `$length` is greater than 1.
	 */
	protected function format_day_of_week_in_month(DateTime $datetime, $pattern, $length)
	{
		if ($length == 1)
		{
			return floor(($datetime->day + 6) / 7);
		}

		return false;
	}

	/*
	 * weekday (E,e,c)
	 */

	/**
	 * Day of week - Use one through three letters for the short day, or four for the full name,
	 * five for the narrow name, or six for the short name. [1..3,4,5,6]
 	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 *
	 * @return string
	 */
	protected function format_day_in_week(DateTime $datetime, $pattern)
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

		$day = $datetime->weekday;

		switch ($pattern)
		{
			case 'E':
			case 'EE':
			case 'EEE':
			case 'eee':
				return $this->calendar->abbreviated_days[$translate[$day]];

			case 'EEEE':
			case 'eeee':
				return $this->calendar->wide_days[$translate[$day]];

			case 'EEEEE':
			case 'eeeee':
				return $this->calendar->narrow_days[$translate[$day]];

			case 'EEEEEE':
			case 'eeeeee':
				return $this->calendar->short_days[$translate[$day]];

			case 'e':
			case 'ee':
			case 'c':
				return $day;

			case 'ccc':
				return $this->calendar->standalone_abbreviated_days[$translate[$day]];

			case 'cccc':
				return $this->calendar->standalone_wide_days[$translate[$day]];

			case 'ccccc':
				return $this->calendar->standalone_narrow_days[$translate[$day]];

			case 'cccccc':
				return $this->calendar->standalone_short_days[$translate[$day]];
		}
	}

	/*
	 * period (a)
	 */

	/**
	 * AM or PM. [1]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string AM or PM designator
	 */
	protected function format_period(DateTime $datetime, $pattern, $length)
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
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_hour12(DateTime $datetime, $pattern, $length)
	{
		$hour = $datetime->hour;
		$hour = ($hour == 12) ? 12 : $hour % 12;

		if ($length == 1)
		{
			return $hour;
		}
		else if ($length == 2)
		{
			return str_pad($hour, 2, '0', STR_PAD_LEFT);
		}
	}

	/**
	 * Hour [0-23]. When used in skeleton data or in a skeleton passed in an API for flexible
	 * data pattern generation, it should match the 24-hour-cycle format preferred by the
	 * locale (H or k); it should not match a 12-hour-cycle format (h or K). Use HH for zero
	 * padding. [1..2]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string
	 */
	protected function format_hour24(DateTime $datetime, $pattern, $length)
	{
		$hour = $datetime->hour;

		if ($length == 1)
		{
			return $hour;
		}
		else if ($length == 2)
		{
			return str_pad($hour, 2, '0', STR_PAD_LEFT);
		}
	}

	/**
	 * Hour [0-11]. When used in a skeleton, only matches K or h, see above. Use KK for zero
	 * padding. [1..2]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern A pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return integer hours in AM/PM format.
	 */
	protected function format_hour_in_period(DateTime $datetime, $pattern, $length)
	{
		$hour = $datetime->hour % 12;

		if ($length == 1)
		{
			return $hour;
		}
		else if ($length == 2)
		{
			return str_pad($hour, 2, '0', STR_PAD_LEFT);
		}
	}

	/**
	 * Hour [1-24]. When used in a skeleton, only matches k or H, see above. Use kk for zero
	 * padding. [1..2]
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return integer
	 */
	protected function format_hour_in_day(DateTime $datetime, $pattern, $length)
	{
		$hour = $datetime->hour;

		if ($hour == 0)
		{
			$hour = 24;
		}

		if ($length == 1)
		{
			return $hour;
		}
		else if ($length == 2)
		{
			return str_pad($hour, 2, '0', STR_PAD_LEFT);
		}
	}

	/*
	 * minute (m)
	 */

	/**
	 * Minute. Use one or two "m" for zero padding.
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition
	 *
	 * @return string minutes.
	 */
	protected function format_minutes(DateTime $datetime, $pattern, $length)
	{
		$minutes = $datetime->minute;

		if ($length == 1)
		{
			return $minutes;
		}
		else if ($length == 2)
		{
			return str_pad($minutes, 2, '0', STR_PAD_LEFT);
		}
	}

	/*
	 * second
	 */

	/**
	 * Second. Use one or two "s" for zero padding.
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string seconds
	 */
	protected function format_seconds(DateTime $datetime, $pattern, $length)
	{
		$seconds = $datetime->second;

		if ($length == 1)
		{
			return $seconds;
		}
		else if ($length == 2)
		{
			return str_pad($seconds, 2, '0', STR_PAD_LEFT);
		}
	}

	/*
	 * zone (z,Z,v)
	 */

	/**
	 * Time Zone.
	 *
	 * @param DateTime $datetime
	 * @param string $pattern a pattern.
	 * @param int $length Number of repetition.
	 *
	 * @return string time zone
	 */
	protected function format_timezone(DateTime $datetime, $pattern, $length)
	{
		if ($pattern{0} === 'z' || $pattern{0} === 'v')
		{
			return $datetime->format('T');
		}
		else if ($pattern{0} === 'Z')
		{
			return $datetime->format('O');
		}
	}
}
