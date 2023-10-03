# Dates

This part covers [calendars](#calendars) and [dates and times](#dates-and-times).

[Unicode Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-dates.html#Contents)

-----

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#parts):

- Part 1: [Core](Core.md) (languages, locales, basic structure)
- Part 2: [General](General.md) (display names & transforms, etc.)
- Part 3: [Numbers](Numbers.md) (number & currency formatting)
- Part 4: [Dates](Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](Supplemental.md) (supplemental data)

-----



## Calendars

Calendars are represented by a [Calendar][] instance, they can be accessed as arrays, and also
provide magic properties to rapidly access days, eras, months and quarters:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository  
 */

$calendar = $repository->locales['fr']->calendars['gregorian'];
# or
$calendar = $repository->locales['fr']->calendar; // because "gregorian" is the default calendar for this locale

$calender['days']['stand-alone']['abbreviated']
# or
$calendar->standalone_abbreviated_days;

$calender['days']['format']['abbreviated']
# or
$calendar->abbreviated_days;
```

This works with days, eras, months, quarters and the following widths: `abbreviated`, `narrow`,
`short`, and `wide`. Here are some examples:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Calendar $calendar 
 */

$calendar->standalone_abbreviated_eras;
$calendar->standalone_narrow_months;
$calendar->standalone_short_quarters;
$calendar->standalone_wide_days;
$calendar->abbreviated_days;
$calendar->narrow_months;
$calendar->short_days;
$calendar->wide_quarters;
```



### Dates and times formatters

From a calendar you can obtain formatters for dates and times.

The following example demonstrates how the dates and times formatters can be accessed and
used.

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository 
 */

$datetime = '2018-11-24 20:12:22 UTC';
$calendar = $repository->locales['fr']->calendar;

echo $calendar['days']['format']['wide']['sun'];    // dimanche
echo $calendar->wide_days['sun'];                   // dimanche

echo $calendar->format_datetime($datetime, 'full'); // samedi 24 novembre 2018 20:12:22 UTC
echo $calendar->format_date($datetime, 'long');     // 24 novembre 2018
echo $calendar->format_time($datetime, 'long');     // 20:12:22 UTC
# or
echo $calendar->datetime_formatter->format($datetime, 'full'); // samedi 24 novembre 2018 20:12:22 UTC
echo $calendar->date_formatter->format($datetime, 'long');     // 24 novembre 2018
echo $calendar->time_formatter->format($datetime, 'long');     // 20:12:22 UTC
```



## Dates and Times

Calendars provide a formatter for dates and times. A width, a skeleton or a pattern can be
used for the formatting. The datetime can be specified as an Unix timestamp, a string or a
`DateTime` instance.

```php
<?php

use ICanBoogie\CLDR\DateTimeFormatter;

/**
 * @var ICanBoogie\CLDR\Calendar $calendar 
 */

$datetime = '2013-11-02 22:23:45 UTC';
$formatter = $calendar->datetime_formatter;

echo $formatter($datetime, "MMM d, y");                 // November 2, 2013
echo $formatter($datetime, "MMM d, y 'at' hh:mm:ss a"); // November 2, 2013 at 10:23:45 PM
echo $formatter($datetime, $formatter::WIDHT_FULL);     // Saturday, November 2, 2013 at 10:23:45 PM UTC
echo $formatter($datetime, $formatter::WIDHT_LONG);     // November 2, 2013 at 10:23:45 PM UTC
echo $formatter($datetime, $formatter::WIDHT_MEDIUM);   // Nov 2, 2013, 10:23:45 PM
echo $formatter($datetime, $formatter::WIDHT_SHORT);    // 11/2/13, 10:23 PM
echo $formatter($datetime, ':Ehm');                     // Sat 10:23 PM
```





### Date formatter

Calendars provide a formatter for dates. A width or a pattern is used for the formatting.

```php
<?php

use ICanBoogie\CLDR\DateFormatter;

/**
 * @var ICanBoogie\CLDR\Calendar $calendar  
 */

$datetime = '2013-11-05 21:22:23';

echo $calendar->format_datetime($datetime, DateFormatter::WIDTH_FULL);   
// Tuesday, November 5, 2013

echo $calendar->format_datetime($datetime, DateFormatter::WIDTH_LONG);   
// November 5, 2013

echo $calendar->format_datetime($datetime, DateFormatter::WIDTH_MEDIUM);
// Nov 5, 2013

echo $calendar->format_datetime($datetime, DateFormatter::WIDTH_SHORT);
// 11/5/13
```

Alternatively, use can use a [DateTimeFormatter] instance:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Calendar $calendar  
 */

$datetime = '2013-11-05 21:22:23';
$formatter = $calendar->datetime_formatter;

echo $formatter($datetime, $formatter::WIDTH_FULL);   
// Tuesday, November 5, 2013

echo $formatter($datetime, $formatter::WIDTH_LONG);   
// November 5, 2013

echo $formatter($datetime, $formatter::WIDTH_MEDIUM);
// Nov 5, 2013

echo $formatter($datetime, $formatter::WIDTH_SHORT);
// 11/5/13
```





### Time formatter

Calendars provide a formatter for times. A width or a pattern is used for the formatting.

```php
<?php

use ICanBoogie\CLDR\TimeFormatter;

/**
 * @var ICanBoogie\CLDR\Calendar $calendar 
 */

$datetime = '2013-11-05 21:22:23 UTC';
 
echo $calendar->format_time($datetime, TimeFormatter::WIDTH_FULL);
// 9:22:23 PM UTC

echo $calendar->format_time($datetime, TimeFormatter::WIDTH_LONG);
// 9:22:23 PM UTC

echo $calendar->format_time($datetime, TimeFormatter::WIDTH_MEDIUM);
// 9:22:23 PM

echo $calendar->format_time($datetime, TimeFormatter::WIDTH_SHORT);
// 9:22 PM
```

Alternatively, you can use a [TimeFormatter][] instance:

```php
<?php

use ICanBoogie\CLDR\TimeFormatter;

/**
 * @var ICanBoogie\CLDR\Calendar $calendar 
 */

$datetime = '2013-11-05 21:22:23 UTC';
$formatter = $calendar->time_formatter;

echo $formatter($datetime, $formatter::WIDTH_FULL);
// 9:22:23 PM UTC
```



### Localized DateTime

`DateTime` can be localized by wrapping them inside a [LocalizedDateTime][] instance, or by using
the `localize` method of the desired locale:

```php
<?php

use ICanBoogie\CLDR\LocalizedDateTime;

/**
 * @var ICanBoogie\CLDR\Repository $repository  
 */

$ldt = new LocalizedDateTime(new \DateTime('2013-11-04 20:21:22 UTC'), $repository->locales['fr']);
# or
$ldt = $repository->locales['fr']->localize(new \DateTime('2013-11-04 20:21:22 UTC'));

echo $ldt->as_full;          // lundi 4 novembre 2013 à 20:21:22 UTC
# or
echo $ldt->format_as_full(); // lundi 4 novembre 2013 à 20:21:22 UTC

echo $ldt->as_long;          // 4 novembre 2013 à 20:21:22 UTC
echo $ldt->as_medium;        // 4 nov. 2013 20:21:22
echo $ldt->as_short;         // 04/11/2013 20:21
```



[Calendar]: ../lib/Calendar.php 
[DateTimeFormatter]: ../lib/DateTimeFormatter.php
[LocalizedDateTime]: ../lib/LocalizedDateTime.php
[TimeFormatter]: ../lib/TimeFormatter.php
