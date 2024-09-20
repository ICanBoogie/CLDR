<?php

namespace ICanBoogie\CLDR\Dates;

use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use ICanBoogie\CLDR\Core\Formatter;
use InvalidArgumentException;

use function ceil;
use function floor;
use function is_array;
use function is_numeric;
use function str_pad;

use const STR_PAD_LEFT;

/**
 * Provides date and time localization.
 *
 * The class allows you to format dates and times in a locale-sensitive manner using
 * {@link https://www.unicode.org/reports/tr35/tr35-72/tr35-dates.html#Date_Format_Patterns Unicode format patterns}.
 */
class DateTimeFormatter implements Formatter
{
    /**
     * Pad a numeric value with zero on its left.
     */
    private static function numeric_pad(int $value, int $length = 2): string
    {
        return str_pad((string)$value, $length, '0', STR_PAD_LEFT);
    }

    /**
     * @param Calendar $calendar
     *     The calendar used by the formatter.
     */
    public function __construct(
        public readonly Calendar $calendar
    ) {
    }

    /**
     * Formats a date according to a pattern.
     *
     * @param float|int|string|DateTimeInterface $datetime
     *     The datetime to format.
     *
     * @return string
     *     The formatted date.
     *
     * @throws \Exception
     *
     * @link https://www.unicode.org/reports/tr35/tr35-72/tr35-dates.html#26-element-datetimeformats
     */
    public function format(
        float|int|string|DateTimeInterface $datetime,
        string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
    ): string {
        $accessor = new DateTimeAccessor($this->ensure_datetime($datetime));
        $pattern = $this->resolve_pattern($pattern_or_length_or_id);
        $tokens = DateFormatPatternParser::parse($pattern);

        $rc = '';

        foreach ($tokens as $token) {
            if (is_array($token)) {
                [ $c, $l ] = $token;

                $f = $this->match_formatter($c, $pattern);
                $token = $f($accessor, $l);
            }

            $rc .= $token;
        }

        return $rc;
    }

    /**
     * @param string $c
     *      A pattern character.
     *
     * @return Closure(DateTimeAccessor, int $length):string
     */
    private function match_formatter(string $c, string $pattern): Closure
    {
        return match ($c) {
            'G' => $this->format_era(...),
            'y' => $this->format_year(...),
//            'Y' => Year (in "Week of Year" based calendars)
//            'u' => Extended year
            'Q' => $this->format_quarter(...),
            'q' => $this->format_standalone_quarter(...),
            'M' => $this->format_month(...),
            'L' => $this->format_standalone_month(...),
//            'l' => Special symbol for Chinese leap month, used in combination with M.
            'w' => $this->format_week_of_year(...),
            'W' => $this->format_week_of_month(...),
            'd' => $this->format_day_of_month(...),
            'D' => $this->format_day_of_year(...),
            'F' => $this->format_day_of_week_in_month(...),
            'h' => $this->format_hour12(...),
            'H' => $this->format_hour24(...),
            'm' => $this->format_minutes(...),
            's' => $this->format_seconds(...),
            'E' => $this->format_day_in_week(...),
            'c' => $this->format_day_in_week_stand_alone(...),
            'e' => $this->format_day_in_week_local(...),
            'a' => $this->format_period(...),
            'k' => $this->format_hour_in_day(...),
            'K' => $this->format_hour_in_period(...),
            'z', 'v' => $this->format_timezone_non_location(...),
            'Z' => $this->format_timezone_basic(...),
            default => throw new InvalidArgumentException("Unsupported date pattern character '$c' used in '$pattern'")
        };
    }

    /**
     * Resolves the specified pattern, which can be a width, a skeleton, or an actual pattern.
     */
    protected function resolve_pattern(
        string|DateTimeFormatLength|DateTimeFormatId $pattern_or_length_or_id
    ): string {
        if ($pattern_or_length_or_id instanceof DateTimeFormatLength) {
            $length = $pattern_or_length_or_id->value;
            $calendar = $this->calendar;
            $datetime_pattern = $calendar['dateTimeFormats-atTime']['standard'][$length]
                ?? $calendar['dateTimeFormats'][$length];
            $date_pattern = $calendar['dateFormats'][$length];
            $time_pattern = $calendar['timeFormats'][$length];

            return strtr($datetime_pattern, [
                '{1}' => $date_pattern,
                '{0}' => $time_pattern
            ]);
        } elseif ($pattern_or_length_or_id instanceof DateTimeFormatId) {
            $id = $pattern_or_length_or_id->id;

            return $this->calendar['dateTimeFormats']['availableFormats'][$id]
                ?? throw new InvalidArgumentException("Unknown DateTime format id: $id");
        }

        return $pattern_or_length_or_id;
    }

    /**
     * Era (G); Replaced with the Era string for the current date.
     *
     * One to three letters for the abbreviated form, four letters for the long form, five for the narrow form: [1..3,
     * 4, 5]
     *
     * @TODO How to support multiple Eras?, e.g. Japanese.
     */
    private function format_era(DateTimeAccessor $datetime, int $length): string
    {
        if ($length > 5) {
            return '';
        }

        $era = match ($this->calendar->id) {
            CalendarId::GREGORIAN => ($datetime->year > 0) ? 1 : 0,
            default => 0,
        };

        return match ($length) {
            1, 2, 3 => $this->calendar->abbreviated_eras[$era],
            4 => $this->calendar->wide_eras[$era],
            5 => $this->calendar->narrow_eras[$era],
            default => '',
        };
    }

    /**
     * Year (y); Normally the length specifies the padding, but for two letters it also specifies the maximum length:
     * [1..n].
     */
    private function format_year(DateTimeAccessor $datetime, int $length): string
    {
        $year = $datetime->year;

        if ($length == 2) {
            $year = $year % 100;
        }

        return self::numeric_pad($year, $length);
    }

    /**
     * Quarter (Q); One or two "Q" for the numerical quarter, three for the abbreviation, or four for the full (wide)
     * name: [1..2, 3, 4]
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
     * Stand-Alone Quarter (q); One or two "q" for the numerical quarter, three for the abbreviation, or four for the
     * full (wide) name: [1..2, 3, 4]
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
     * Month (M|L)
     */

    /**
     * Month (M); One or two "M" for the numerical month, three for the abbreviation, four for the full name, or five
     * for the narrow name: [1..2, 3, 4, 5]
     */
    private function format_month(DateTimeAccessor $datetime, int $length): string
    {
        return $this->do_format_month(
            datetime: $datetime,
            length: $length,
            abbreviated: $this->calendar->abbreviated_months,
            wide: $this->calendar->wide_months,
            narrow: $this->calendar->narrow_months,
        );
    }

    /**
     * Stand-Alone Month (L); One or two "L" for the numerical month, three for the abbreviation, or four for the full
     * (wide) name, or 5 for the narrow name: [1..2, 3, 4, 5]
     */
    private function format_standalone_month(DateTimeAccessor $datetime, int $length): string
    {
        return $this->do_format_month(
            datetime: $datetime,
            length: $length,
            abbreviated: $this->calendar->standalone_abbreviated_months,
            wide: $this->calendar->standalone_wide_months,
            narrow: $this->calendar->standalone_narrow_months,
        );
    }

    /**
     * @param array<int, string> $abbreviated
     * @param array<int, string> $wide
     * @param array<int, string> $narrow
     */
    private function do_format_month(
        DateTimeAccessor $datetime,
        int $length,
        array $abbreviated,
        array $wide,
        array $narrow,
    ): string {
        if ($length > 5) {
            return '';
        }

        /** @var int<1, 5> $length */

        $month = $datetime->month;

        return match ($length) {
            1 => (string)$month,
            2 => self::numeric_pad($month),
            3 => $abbreviated[$month],
            4 => $wide[$month],
            5 => $narrow[$month],
        };
    }

    /*
     * Week (w|W)
     */

    /**
     * Week of the year (w); [1..2].
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
     * Week of the month (W); [1].
     */
    private function format_week_of_month(DateTimeAccessor $datetime, int $length): string
    {
        if ($length > 1) {
            return '';
        }

        return (string)ceil($datetime->day / 7) ?: "0";
    }

    /*
     * Day (d,D,F)
     */

    /**
     * Day of the month (d); [1..2].
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
     * Day of the year (D); [1..3].
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
     * Day of the week in a month (F); For example, "2nd Wed in July": [1].
     */
    private function format_day_of_week_in_month(DateTimeAccessor $datetime, int $length): string
    {
        if ($length > 1) {
            return '';
        }

        return (string)floor(($datetime->day + 6) / 7);
    }

    /*
     * Weekday (E,e,c)
     */

    /**
     * Weekday (E); One through three letters for the short day, or four for the full name, five for the narrow name, or
     * six for the short name: [1..3, 4, 5, 6].
     */
    private function format_day_in_week(DateTimeAccessor $datetime, int $length): string
    {
        if ($length > 6) {
            return '';
        }

        $day = $datetime->weekday;
        $code = $this->name_for_day_code($day);
        $calendar = $this->calendar;

        return match ($length) {
            1, 2, 3 => $calendar->abbreviated_days[$code],
            4 => $calendar->wide_days[$code],
            5 => $calendar->narrow_days[$code],
            6 => $calendar->short_days[$code],
            default => '',
        };
    }

    /**
     * Stand-Alone weekday (e); One letter for the local numeric value (same as 'e'), three for the abbreviated day
     * name, four for the full (wide) name, five for the narrow name, or six for the short name.
     */
    private function format_day_in_week_stand_alone(DateTimeAccessor $datetime, int $length): string
    {
        if ($length == 2 || $length > 6) {
            return '';
        }

        $day = $datetime->weekday;

        if ($length == 1) {
            return (string)$day;
        }

        /** @var int<3, 6> $length */

        $days = match ($length) {
            3 => $this->calendar->standalone_abbreviated_days,
            4 => $this->calendar->standalone_wide_days,
            5 => $this->calendar->standalone_narrow_days,
            6 => $this->calendar->standalone_short_days,
        };

        $code = $this->name_for_day_code($day);

        return $days[$code];
    }

    /**
     * Local day of the week (c); Same as E except adds a numeric value that will depend on the local starting day of
     * the week, using one or two letters; For example, Monday is the first day of the week.
     */
    private function format_day_in_week_local(DateTimeAccessor $datetime, int $length): string
    {
        if ($length < 3) {
            return (string)$datetime->weekday;
        }

        return $this->format_day_in_week($datetime, $length);
    }

    /**
     * Period (a); AM or PM. [1]
     *
     * @return string AM or PM designator.
     */
    private function format_period(DateTimeAccessor $datetime): string
    {
        return $this->calendar['dayPeriods']['format']['abbreviated'][$datetime->hour < 12 ? 'am' : 'pm'];
    }

    /*
     * hour (h,H,K,k)
     */

    /**
     * Hour [1-12] (h); When used in skeleton data or in a skeleton passed in an API for flexible data pattern
     * generation, it should match the 12-hour-cycle format preferred by the locale (h or K); it shouldn't match a
     * 24-hour-cycle format (H or k).
     *
     * Use "hh" for zero-padding; [1..2].
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
     * Hour [0-23] (H); When used in skeleton data or in a skeleton passed in an API for flexible data pattern
     * generation, it should match the 24-hour-cycle format preferred by the locale (H or k); it shouldn't match a
     * 12-hour-cycle format (h or K).
     *
     * Use "HH" for zero-padding; [1..2].
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
     * Hour [0-11] (K); When used in a skeleton, only matches K or h, see above.
     *
     * Use "KK" for zero-padding; [1..2].
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
     * Hour [1-24] (k); When used in a skeleton, only matches k or H, see above.
     *
     * Use "kk" for zero-padding; [1..2].
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

    /**
     * Minute (m); One or two "m" for zero-padding.
     */
    private function format_minutes(DateTimeAccessor $datetime, int $length): string
    {
        return $this->format_minutes_or_seconds($datetime, $length, 'minute');
    }

    /**
     * Second (s); One or two "s" for zero-padding.
     */
    private function format_seconds(DateTimeAccessor $datetime, int $length): string
    {
        return $this->format_minutes_or_seconds($datetime, $length, 'second');
    }

    /**
     * Minute (m); One or two "m" for zero-padding.
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
     * The ISO8601 basic format (z).
     */
    private function format_timezone_basic(DateTimeAccessor $datetime): string
    {
        return $datetime->format('O');
    }

    /**
     * The specific non-location format (Z, v).
     */
    private function format_timezone_non_location(DateTimeAccessor $datetime): string
    {
        $str = $datetime->format('T');

        return $str === 'Z' ? 'UTC' : $str;
    }

    /**
     * @throws \Exception if the {@see DateTimeImmutable} instance can't be created.
     */
    private function ensure_datetime(float|int|string|DateTimeInterface $datetime): DateTimeInterface
    {
        if ($datetime instanceof DateTimeInterface) {
            return $datetime;
        }

        return new DateTimeImmutable(is_numeric($datetime) ? "@$datetime" : (string)$datetime);
    }

    /**
     * @param int<1, 7> $day
     *     A day code.
     */
    private function name_for_day_code(int $day): string
    {
        return match ($day) {
            1 => 'mon',
            2 => 'tue',
            3 => 'wed',
            4 => 'thu',
            5 => 'fri',
            6 => 'sat',
            7 => 'sun',
        };
    }
}
