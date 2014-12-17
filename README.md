# CLDR [![Build Status](https://travis-ci.org/ICanBoogie/CLDR.svg?branch=master)](https://travis-ci.org/ICanBoogie/CLDR)

The __CLDR__ package provides means to internationalize your application by leveraging the
data and conventions defined by the [Unicode Common Locale Data Repository](http://cldr.unicode.org/) (CLDR).

The package target the [CLDR version 26](http://cldr.unicode.org/index/downloads/cldr-26) from
which data is retrieved transparently when required.





## Instantiating the repository

The CLDR is represented by a [Repository][] instance, from which you can access
the available locales or supplemental data. When necessary, the repository fetches the required
data through a provider. You can use the default provider, or define your own.

The following example demonstrates how such a repository is instantiated and how the
available locales and supplemental data are accessed:

```php
<?php

namespace ICanBoogie\CLDR;

$provider = new Provider
(
	new RunTimeCache(new FileCache('/path/to/cached_repository')),
	new Retriever
);

$repository = new Repository($provider);

$english_locale = $repository->locales['en'];
$french_locale = $repository->locales['fr'];

$supplemental = $repository->supplemental;
# reading the default calendar
echo $supplemental['calendarPreferenceData']['001']; // gregorian
```





### Localized objects

Instances can be localized using the `localize()` method of the [Locale][] class. The method
tries its best to find a suitable _localizer_, and it helps if the class of the instance
[LocalizationAwareInterface][], or if a `ICanBoogie\CLDR\Localized<class_base_name>` class is
defined. 

```php
<?php

$datetime = new \DateTime;
$localized_datetime = $repository->locales['fr']->localize($datetime);
```

Instances that can be localized usually implement the `localize()` method.

```php
<php

echo $repository->territories['FR']->localize('fr')->name; // France
```





## Locales

The data and conventions of a locale are represented by a [Locale][] instance, which is used as
an array to access the various data such as calendars, characters, currencies, delimiters,
languages, territories and more.

```php
<?php

$locale = $repository->locales['fr'];

echo $locale['characters']['auxiliary'];      // [á å ä ã ā ē í ì ī ñ ó ò ö ø ú ǔ]
echo $locale['delimiters']['quotationStart']; // «
echo $locale['territories']['TF'];            // Terres australes françaises
```

Locales provide a collection of calendars, and the `calendar` property is often used to
obtain the default calendar of a locale.

```php
<?php

$locale = $repository->locales['fr'];

echo $locale['ca-gregorian']['days']['format']['wide']['sun'];         // dimanche
# or using the calendar collection
echo $locale->calendars['gregorian']['days']['format']['wide']['sun']; // dimanche
# or because 'gregorian' is the default calendar for this locale
echo $locale->calendar['days']['format']['wide']['sun'];               // dimanche
```





## Localized locales

A localized locale can be obtained with the `localize()` method, or the `localize()` method
of the desired locale.

```php
<?php

$locale = $repository->locales['fr'];

echo $locale->localize('fr')->name;                         // Français
# or
echo $repository->locales['fr']->localize($locale)->name;   // Français
```





## Territories

The information about a territory is represented by a [Territory][] instance, which aggregates
information that is actually scattered across the CLDR.

```php
<?php

$territory = $repository->territories['FR'];

echo $territory;                                    // FR
echo $territory->currency;                          // EUR
echo $territory->currency_at('1977-06-06');         // FRF
echo $territory->currency_at('now');                // EUR

echo $territory->language;                          // fr
echo $territory->population;                        // 66259000

echo $territory->name_as('fr-FR');                  // France
echo $territory->name_as('it');                     // Francia
echo $territory->name_as('ja');                     // フランス

echo $territory->name_as_fr_FR;                     // France
echo $territory->name_as_it;                        // Francia
echo $territory->name_as_ja;                        // フランス

echo $repository->territories['FR']->first_day;     // mon
echo $repository->territories['EG']->first_day;     // sat
echo $repository->territories['BS']->first_day;     // sun

echo $repository->territories['AE']->weekend_start; // fri
echo $repository->territories['AE']->weekend_end;   // sat
```





### Localized territories

A localized territory can be obtained with the `localize()` method, or the `localize()` method of
the desired locale.

```php
<?php

$territory = $repository->territories['FR'];

$localized_territory = $territory->localize('fr');
# or
$localized_territory = $repository->locales['fr']->localize($territory);

echo $territory->localize('fr')->name;   // France
echo $territory->localize('it')->name;   // Francia
echo $territory->localize('ja')->name;   // フランス
```





## Calendars

Calendars are represented by a [Calendar][] instance, they can be accessed as arrays, and also
provide magic properties to rapidly access days, eras, months and quarters:

```php
<?php

namespace ICanBoogie\CLDR;

$calendar = new Calendar($repository->locales['fr'], $repository->locales['fr']['ca-gregorian']);
# or
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





### Dates and times formatters

From a calendar you can obtain formatters for dates and times.

The following example demonstrates how the dates and times formatters can be accessed and
used.

```php
<?php

$datetime = '2013-11-05 20:12:22 UTC';
$calendar = $repository->locales['fr']->calendar;

echo $calendar->datetime_formatter->format($datetime, 'long'); // mardi 5 novembre 2013 20:12:22 UTC
echo $calendar->date_formatter->format($datetime, 'long');     // mardi 5 novembre 2013
echo $calendar->time_formatter->format($datetime, 'long');     // 20:12:22 UTC
```





## Currencies

Currencies are represented by instances of [Currency][]. You can create the instance yourself or
get one through the currency collection.

```php
<php

$euro = new Currency($repository, 'EUR')
# or
$euro = $repository->currencies['EUR'];
```





### Localized currencies

A localized currency can be obtained with the `localize()` method, or the `localize()` method
of the desired locale.

```php
<php

$currency = new Currency($repository, 'EUR')

$localized_currency = $currency->localize('fr');
# or
$localized_currency = $repository->locale['fr']->localize($currency);

echo $localized_euro->name;             // euro
echo $localized_euro->name(1);          // euro
echo $localized_euro->name(10);         // euros
echo $localized_euro->format(12345.67); // 1 2345,67 €
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

$datetime = '2013-11-02 22:23:45 UTC';

echo $formatter($datetime, "MMM d, y");                 // November 2, 2013
echo $formatter($datetime, "MMM d, y 'at' hh:mm:ss a"); // November 2, 2013 at 10:23:45 PM
echo $formatter($datetime, 'full');                     // Saturday, November 2, 2013 at 10:23:45 PM UTC
echo $formatter($datetime, 'long');                     // November 2, 2013 at 10:23:45 PM UTC
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

$datetime = '2013-11-05 21:22:23 UTC';

echo $formatter($datetime, 'full');   // 9:22:23 PM UTC
echo $formatter($datetime, 'long');   // 9:22:23 PM UTC
echo $formatter($datetime, 'medium'); // 9:22:23 PM
echo $formatter($datetime, 'short');  // 9:22 PM
```





### Localized DateTime

`DateTime` can be localized by wrapping them inside a [LocalizedDateTime][] instance, or by using
the `localize` method of the desired locale: 

```php
<?php

namespace ICanBoogie\CLDR;

$ldt = new LocalizedDateTime(new \DateTime('2013-11-04 20:21:22 UTC'), $repository->locales['fr']);
# or
$ldt = $repository->locales['fr']->localize(new \DateTime('2013-11-04 20:21:22 UTC'));

echo $ldt->as_full;          // lundi 4 novembre 2013 20:21:22 UTC
# or
echo $ldt->format_as_full(); // lundi 4 novembre 2013 20:21:22 UTC

echo $ldt->as_long;          // 4 novembre 2013 20:21:22 UTC
echo $ldt->as_medium;        // 4 nov. 2013 20:21:22
echo $ldt->as_short;         // 04/11/2013 20:21
```





----------





## Requirements

The package requires PHP 5.3 or later, and the [cURL extension](http://www.php.net/manual/en/book.curl.php).





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/):

```
$ composer require icanboogie/cldr
```

The following packages are required, you might want to check them out:

- [icanboogie/common](https://github.com/ICanBoogie/Common)
- [icanboogie/datetime](https://github.com/ICanBoogie/DateTime)





### Cloning the repository

The package is [available on GitHub](https://github.com/ICanBoogie/CLDR), its repository can be
cloned with the following command line:

	$ git clone https://github.com/ICanBoogie/CLDR.git





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

[![Build Status](https://travis-ci.org/ICanBoogie/CLDR.svg?branch=master)](https://travis-ci.org/ICanBoogie/CLDR)





## License

ICanBoogie/CLDR is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.





[CLDR]: http://www.unicode.org/repos/cldr-aux/json/26/
[I18n library]: https://github.com/ICanBoogie/I18n
[Calendar]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Calendar.html
[Currency]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Currency.html
[Repository]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Repository.html
[Locale]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Locale.html
[LocalizationAwareInterface]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.LocalizationAwareInterface.html
[LocalizedDateTime]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.LocalizedDateTime.html
[Territory]: http://icanboogie.org/docs/class-ICanBoogie.CLDR.Territory.html
