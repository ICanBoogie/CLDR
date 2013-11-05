# CLDR [![Build Status](https://travis-ci.org/ICanBoogie/CLDR.png?branch=master)](https://travis-ci.org/ICanBoogie/CLDR)

The CLDR package provides a simple interface to the [Unicode Common Locale Data Repository](http://cldr.unicode.org/) (CLDR).
When required, locale data is transparently retrieved from the [CLDR][] and cached for future
usage.

Although this package provides some internationalization features, currently it's main purpose is
to provide the means to obtain locale data. If you are looking for an internationalization API
you might be interested in the [I18n library][].

Note: The package targets [CLDR version 24](http://cldr.unicode.org/index/downloads/cldr-24).





## Usage

```php
<?php

namespace ICanBoogie\CLDR;

$provider = new Provider
(
	new RunTimeCache(new FileCache('/path/to/cached_repository')),
	new Retriever
);

$repository = new Repository($provider);

$locale = $repository->locales['fr'];

echo $locale->calendars['gregorian']['days']['format']['wide']['sun']; // dimanche

$repository->supplemental['calendarPreferenceData']['001']; // gregorian
```





## Calendars

Calendars are represented by a [Calendar][] instance, they can be accessed as arrays, and also
provide magic properties to rapidly access days, eras, months and quarters:

```php
<?php

namespace ICanBoogie\CLDR;

$calendar = $repository->locales['fr']->calendars['gregorian'];
# or
$calendar = $repository->locales['fr']->calendar; // because "gregorian" is the default calendar for this locale

$calender->standalone_abbreviated_days;
# or $calender['days']['stand-alone']['abbreviated'];

$calender->abbreviated_days;
# or $calender['days']['format']['abbreviated'];
```

This works with days, eras, months, quarters and the following widths: `abbreviated`, `narrow`,
`short`, and `wide`. Here are some examples:

```php
<?php

$calender->standalone_abbreviated_eras;
$calender->standalone_narrow_months;
$calender->standalone_short_quarters;
$calender->standalone_wide_days;
$calender->abbreviated_days;
$calender->narrow_months;
$calender->short_days;
$calender->wide_quarters;
```





## Dates and Times

Calendars provide a formatter for dates and times. A width, a skeleton or a pattern can be
used for the formatting. The datetime can be specified as an Unix timestamp, a string or a 
`DateTime` instance.

```php
<?php

namespace ICanBoogie\CLDR;

$formatter = new DateTimeFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->datetime_formatter;

$datetime = '2013-11-02 22:23:45';

echo $formatter($datetime, "MMM d, y");                 // November 2, 2013 at 10:23:45 PM
echo $formatter($datetime, "MMM d, y 'at' hh:mm:ss a"); // November 2, 2013 at 10:23:45 PM
echo $formatter($datetime, 'full');                     // Saturday, November 2, 2013 at 10:23:45 PM CET
echo $formatter($datetime, 'long');                     // November 2, 2013 at 10:23:45 PM CET
echo $formatter($datetime, 'medium');                   // Nov 2, 2013, 10:23:45 PM
echo $formatter($datetime, 'short');                    // 11/2/13, 10:23 PM
echo $formatter($datetime, ':Ehm');                     // Sat 10:23 PM
```





### Date formatter

Calendars provide a formatter for dates. A width or a pattern is used for the formatting.

```php
<?php

namespace ICanBoogie\CLDR;

$formatter = new DateFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->date_formatter;

$datetime = '2013-11-05 21:22:23';

echo $formatter($datetime, 'full');   // Tuesday, November 5, 2013
echo $formatter($datetime, 'long');   // November 5, 2013
echo $formatter($datetime, 'medium'); // Nov 5, 2013
echo $formatter($datetime, 'short');  // 11/5/13
```





### Time formatter

Calendars provide a formatter for times. A width or a pattern is used for the formatting.

```php
<?php

namespace ICanBoogie\CLDR;

$formatter = new TimeFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->time_formatter;

$datetime = '2013-11-05 21:22:23';

echo $formatter($datetime, 'full');   // 9:22:23 PM CET
echo $formatter($datetime, 'long');   // 9:22:23 PM CET
echo $formatter($datetime, 'medium'); // 9:22:23 PM
echo $formatter($datetime, 'short');  // 9:22 PM
```





### Localized DateTime

`DateTime` can be localized by wrapping them inside a [LocalizedDateTime][] instance:

```php
<?php

namespace ICanBoogie\CLDR;

$ldt = new LocalizedDateTime(new \DateTime('2013-11-04 20:21:22 UTC'), $repository->locales['fr']);

echo $ldt->as_full;          // lundi 4 novembre 2013 20:21:22 UTC
# or
echo $ldt->format_as_full(); // lundi 4 novembre 2013 20:21:22 UTC

echo $ldt->as_long;          // 4 novembre 2013 20:21:22 UTC
echo $ldt->as_medium;        // 4 nov. 2013 20:21:22
echo $ldt->as_short;         // 04/11/2013 20:21
```





## Requirements

The package requires PHP 5.3 or later, and the [cURL extension](http://www.php.net/manual/en/book.curl.php).





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/).
Create a `composer.json` file and run `php composer.phar install` command to install it:

```json
{
	"minimum-stability": "dev",
	"require": {
		"icanboogie/cldr": "*"
	}
}
```

The following packages are required, you might want to check them out:

- [icanboogie/common](https://github.com/ICanBoogie/Common)
- [icanboogie/datetime](https://github.com/ICanBoogie/DateTime)





### Cloning the repository

The package is [available on GitHub](https://github.com/ICanBoogie/CLDR), its repository can be
cloned with the following command line:

	$ git clone git://github.com/ICanBoogie/CLDR.git





## Documentation

The package is documented as part of the [ICanBoogie](http://icanboogie.org/) framework
[documentation](http://icanboogie.org/docs/). You can generate the documentation for the package
and its dependencies with the `make doc` command. The documentation is generated in the `docs`
directory. [ApiGen](http://apigen.org/) is required. You can later clean the directory with
the `make clean` command.





## Testing

The test suite is ran with the `make test` command. [Composer](http://getcomposer.org/) is
automatically installed as well as all dependencies required to run the suite. You can later
clean the directory with the `make clean` command.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://travis-ci.org/ICanBoogie/CLDR.png?branch=master)](https://travis-ci.org/ICanBoogie/CLDR)





## License

ICanBoogie/CLDR is licensed under the New BSD License - See the LICENSE file for details.





[CLDR]: http://www.unicode.org/repos/cldr-aux/json/24/
[I18n library]: https://github.com/ICanBoogie/I18n
[Calendar]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Calendar.html
[LocalizedDateTime]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.LocalizedDateTime.html