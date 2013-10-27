# CLDR [![Build Status](https://travis-ci.org/ICanBoogie/CLDR.png?branch=master)](https://travis-ci.org/ICanBoogie/CLDR)

The CLDR package provides a simple interface to the [Unicode Common Locale Data Repository](http://cldr.unicode.org/) (CLDR).
When required, locale data is transparently retrieved from the [CLDR][] and cached for future
usage.

This package is not meant to provide an internationalization API but rather the means to obtain the
data required for such an API. If you are looking for such an API, you might be interested in the
[I18n library][].

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

echo $locale['ca-gregorian']['days']['format']['wide']['sun']; // dimanche

$repository->supplemental['calendarPreferenceData']['001']; // gregorian
```





## Calendars

Calendars are represented by a [Calendar][] instance, they can be accessed as arrays, and also
provide magic properties to rapidly access days, eras, months and quarters:

```php
<?php

$calendar = $repository->locales['fr']->calendars['gregorian'];

$calender->standalone_abbreviated_days;
# or $calender['days']['stand-alone']['abbreviated'];

$calender->standalone_abbreviated_eras;
$calender->standalone_abbreviated_months;
$calender->standalone_abbreviated_quarters;
$calender->standalone_narrow_days;
$calender->standalone_narrow_eras;
$calender->standalone_narrow_months;
$calender->standalone_narrow_quarters;
$calender->standalone_short_days;
$calender->standalone_short_eras;
$calender->standalone_short_months;
$calender->standalone_short_quarters;
$calender->standalone_wide_days;
$calender->standalone_wide_eras;
$calender->standalone_wide_months;
$calender->standalone_wide_quarters;
$calender->abbreviated_days;
$calender->abbreviated_eras;
$calender->abbreviated_months;
$calender->abbreviated_quarters;
$calender->narrow_days;
$calender->narrow_eras;
$calender->narrow_months;
$calender->narrow_quarters;
$calender->short_days;
$calender->short_eras;
$calender->short_months;
$calender->short_quarters;
$calender->wide_days;
$calender->wide_eras;
$calender->wide_months;
$calender->wide_quarters;
```





## Requirements

The package requires PHP 5.3 or later.





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