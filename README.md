# CLDR

[![Packagist](https://img.shields.io/packagist/v/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/CLDR/master.svg)](https://scrutinizer-ci.com/g/ICanBoogie/CLDR)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/CLDR/master.svg)](https://coveralls.io/r/ICanBoogie/CLDR)
[![Downloads](https://img.shields.io/packagist/dt/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr)

The __CLDR__ package provides means to internationalize your application by leveraging the data and
conventions defined by the [Unicode Common Locale Data Repository](http://cldr.unicode.org/) (CLDR).
It offers many helpful locale information and data (such as locale names for territories,
languages, days…) as well as formatters for numbers, currencies, dates and times, units, sequences,
lists…

> **Note**
>
> The package targets [CLDR version 41](https://github.com/unicode-org/cldr-json/tree/41.0.0)—[Revision 66](https://www.unicode.org/reports/tr35/tr35-66/tr35.html).



#### Example usage

```php
<?php

/* @var ICanBoogie\CLDR\Repository $repository */

# You get a locale from the repository, here the locale for French.
$fr = $repository->locales['fr'];

# You can use a locale instance as an array to get data
echo $fr['characters']['auxiliary'];                // [á å ä ã ā ē í ì ī ñ ó ò ö ø ú ǔ]
echo $fr['delimiters']['quotationStart'];           // «
echo $fr['territories']['TF'];                      // Terres australes françaises

# You can localize it, to get its local name for example
echo $fr->localize($fr)->name;                      // Français

# You can format numbers, percents, currencies, and lists directly from there
echo $fr->format_number(12345.67);                  // 12 345,67
echo $fr->format_percent(.1234567);                 // 12 %
echo $fr->format_currency(12345.67, 'EUR');         // 12 345,67 €
echo $fr->format_list([ "Un", "deux", "trois" ]);   // Un, deux et trois

# You can get the default calendar for that locale, and access its data
$calendar = $fr->calendar;
echo $calendar['days']['format']['wide']['sun'];    // dimanche
echo $calendar->wide_days['sun'];                   // dimanche

# You can use the calendar to format dates and times, or both
$datetime = '2018-11-24 20:12:22 UTC';
echo $calendar->format_date($datetime, 'long');     // 24 novembre 2018
echo $calendar->format_time($datetime, 'long');     // 20:12:22 UTC
echo $calendar->format_datetime($datetime, 'full'); // samedi 24 novembre 2018 à 20:12:22 UTC

# Alternatively, you can localize a DateTimeInterface instance and get formatted dates of various length
$datetime = new \DateTime('2013-11-04 20:21:22 UTC');
$fr_datetime = $fr->localize($datetime);
echo $fr_datetime->as_full;                         // lundi 4 novembre 2013 à 20:21:22 UTC
echo $fr_datetime->as_long;                         // 4 novembre 2013 à 20:21:22 UTC
echo $fr_datetime->as_medium;                       // 4 nov. 2013 20:21:22
echo $fr_datetime->as_short;                        // 04/11/2013 20:21

# You can format units
$units = $repository->locales['en']->units;
echo $units->duration_hour->name;                   // hours
echo $units->duration_hour->short_name;             // h
echo $units->duration_hour(1);                      // 1 hour
echo $units->duration_hour(23);                     // 23 hours
echo $units->duration_hour(23)->as_short;           // 23 hr
echo $units->duration_hour(23)->as_narrow;          // 23h

# You can format a unit per another unit
echo $units->volume_liter(12.345)->per($units->duration_hour);
// 12.345 liters per hour
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_short;
// 12.345 L/h
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_narrow;
// 12.345L/h

# You can format sequences of units
$units->sequence->angle_degree(5)->duration_minute(30)->as_narrow;
// 5° 30m
$units->sequence->length_foot(3)->length_inch(2)->as_short;
// 3 ft, 2 in

# You can access plural rules
$repository->plurals->rule_for(1.5, 'fr'); // one
$repository->plurals->rule_for(2, 'fr');   // other
$repository->plurals->rule_for(2, 'ar');   // two

# You can access currencies and their localized data
$euro = $repository->currencies['EUR'];
$fr_euro = $euro->localize('fr');
echo $fr_euro->name;
echo $fr_euro->name_for(1);                         // euro
echo $fr_euro->name_for(10);                        // euros
echo $fr_euro->format(12345.67);                    // 12 345,67 €

# You can access territories and their localized data
$territory = $repository->territories['FR'];
echo $territory;                                    // FR
echo $territory->currency;                          // EUR
echo $territory->currency_at('1977-06-06');         // FRF
echo $territory->currency_at('now');                // EUR
echo $territory->name_as('fr-FR');                  // France
echo $territory->name_as('it');                     // Francia
echo $territory->name_as('ja');                     // フランス
echo $repository->territories['FR']->first_day;     // mon
echo $repository->territories['EG']->first_day;     // sat
echo $repository->territories['BS']->first_day;     // sun
echo $repository->territories['AE']->weekend_start; // fri
echo $repository->territories['AE']->weekend_end;   // sat
echo $territory->localize('fr')->name;              // France
echo $territory->localize('it')->name;              // Francia
echo $territory->localize('ja')->name;              // フランス
```



#### Installation

```bash
composer require icanboogie/cldr
```



## Documentation

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#parts):

- Part 1: [Core](docs/Core.md) (languages, locales, basic structure)
- Part 2: [General](docs/General.md) (display names & transforms, etc.)
- Part 3: [Numbers](docs/Numbers.md) (number & currency formatting)
- Part 4: [Dates](docs/Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](docs/Supplemental.md) (supplemental data)



## Getting started

CLDR is represented by a [Repository][] instance, from which data is accessed. When required, data
is retrieved through a provider. The _web_ provider fetches data from the JSON distribution [hosted
on GitHub][2]. In order to avoid hitting the web with every request, a collection of caches is used,
each with its own strategy.

The following example demonstrates how a repository can be instantiated:

```php
<?php

namespace ICanBoogie\CLDR;

use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RedisCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\WebProvider;

/* @var \Redis $redis_client */

$provider = new CachedProvider(
    new WebProvider,
    new CacheCollection([
        new RunTimeCache,
        new RedisCache($redis_client),
        new FileCache("/path/to/storage")
    ])
);

$cldr = new Repository($provider);
```



### Accessing the repository

The repository can be accessed like a big array, but it also provides interfaces to the most
important data such as locales, territories, numbers, currencies…

The following example demonstrates how the repository can be used to access locales and
supplemental data:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository
 */

$english_locale = $repository->locales['en'];
$french_locale = $repository->locales['fr'];

$repository->available_locales;            // [ … 'en', …, 'fr', … ];
$repository->is_locale_available('fr');    // true
$repository->is_locale_available('fr-FR'); // false

$supplemental = $repository->supplemental;
# reading the default calendar
echo $supplemental['calendarPreferenceData']['001']; // gregorian
```



----------



## Continuous Integration

The project is continuously tested by [GitHub actions](https://github.com/ICanBoogie/CLDR/actions).

[![Tests](https://github.com/ICanBoogie/CLDR/workflows/test/badge.svg?branch=master)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Atest)
[![Static Analysis](https://github.com/ICanBoogie/CLDR/workflows/static-analysis/badge.svg?branch=master)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Astatic-analysis)



## Code of Conduct

This project adheres to a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in
this project and its community, you are expected to uphold this code.



## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.



## License

**icanboogie/cldr** is released under the [BSD-3-Clause](LICENSE).



[ICanBoogie]:                 https://icanboogie.org/
[Calendar]:                   lib/Calendar.php
[Currency]:                   lib/Currency.php
[FileCache]:                  lib/Cache/FileCache.php
[ListFormatter]:              lib/ListFormatter.php
[Locale]:                     lib/Locale.php
[Localizable]:                lib/Localizable.php
[LocalizedDateTime]:          lib/LocalizedDateTime.php
[NumberFormatter]:            lib/NumberFormatter.php
[Repository]:                 lib/Repository.php
[Territory]:                  lib/Territory.php

[2]:                          https://github.com/unicode-cldr
