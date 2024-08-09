# CLDR

[![Packagist](https://img.shields.io/packagist/v/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/CLDR/master.svg)](https://scrutinizer-ci.com/g/ICanBoogie/CLDR)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/CLDR/master.svg)](https://coveralls.io/r/ICanBoogie/CLDR)
[![Downloads](https://img.shields.io/packagist/dt/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr)

The __CLDR__ package helps internationalize your application by leveraging the data and conventions
defined by the [Unicode Common Locale Data Repository](http://cldr.unicode.org/) (CLDR). It offers
helpful locale information and data (such as locale names for territories, languages, days…) as well
as formatters for numbers, currencies, dates and times, units, sequences, lists…

> **Note**
>
> The package targets [CLDR version 45](https://github.com/unicode-org/cldr-json/tree/45.0.0); [Revision 72](https://www.unicode.org/reports/tr35/tr35-72/tr35.html).



#### Example usage

```php
<?php

use ICanBoogie\CLDR\Currency;

/* @var ICanBoogie\CLDR\Repository $repository */

# You get a locale from the repository, here the locale for French.
$fr = $repository->locale_for('fr');

# You can use a locale instance as an array
echo $fr['characters']['auxiliary'];                // [á å ä ã ā ē í ì ī ñ ó ò ö ø ú ǔ]
echo $fr['delimiters']['quotationStart'];           // «
echo $fr['territories']['TF'];                      // Terres australes françaises

# You can localize it and get its local name
echo $fr->localize($fr)->name;                      // Français

# You can use it to format numbers, percents, currencies, lists…
echo $fr->format_number(12345.67);                  // 12 345,67
echo $fr->format_percent(.1234567);                 // 12 %
echo $fr->format_currency(12345.67, 'EUR');         // 12 345,67 €
echo $fr->format_list([ "Un", "deux", "trois" ]);   // Un, deux et trois

# You can get the default calendar for a locale and access its data
$calendar = $fr->calendar;
echo $calendar['days']['format']['wide']['sun'];    // dimanche
echo $calendar->wide_days['sun'];                   // dimanche

# You can use the calendar to format dates, times, or both
$datetime = '2018-11-24 20:12:22 UTC';
echo $calendar->format_date($datetime, 'long');     // 24 novembre 2018
echo $calendar->format_time($datetime, 'long');     // 20:12:22 UTC
echo $calendar->format_datetime($datetime, 'full'); // samedi 24 novembre 2018 à 20:12:22 UTC

# Alternatively, you can localize a DateTimeInterface and get formatted dates of various lengths
$datetime = new \DateTime('2013-11-04 20:21:22 UTC');
$fr_datetime = $fr->localize($datetime);
echo $fr_datetime->as_full;                         // lundi 4 novembre 2013 à 20:21:22 UTC
echo $fr_datetime->as_long;                         // 4 novembre 2013 à 20:21:22 UTC
echo $fr_datetime->as_medium;                       // 4 nov. 2013 20:21:22
echo $fr_datetime->as_short;                        // 04/11/2013 20:21

# You can format units
$units = $repository->locale_for('en')->units;
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
$euro = Currency::of('EUR');
$fr_euro = $euro->localize($fr);
echo $fr_euro->name;
echo $fr_euro->name_for(1);      // euro
echo $fr_euro->name_for(10);     // euros
echo $fr_euro->format(12345.67); // 12 345,67 €

# You can access territories and their localized data
$territory = $repository->territories['FR'];
echo $territory;                                       // FR
echo $territory->currency;                             // EUR
echo $territory->currency_at('1977-06-06');            // FRF
echo $territory->currency_at('now');                   // EUR
echo $territory->name_as('fr');        // France
echo $territory->name_as('it');        // Francia
echo $territory->name_as('ja');        // フランス
echo $repository->territories['FR']->first_day;        // mon
echo $repository->territories['EG']->first_day;        // sat
echo $repository->territories['BS']->first_day;        // sun
echo $repository->territories['AE']->weekend_start;    // fri
echo $repository->territories['AE']->weekend_end;      // sat
echo $territory->localize('fr')->name; // France
echo $territory->localize('it')->name; // Francia
echo $territory->localize('ja')->name; // フランス
```



#### Installation

```bash
composer require icanboogie/cldr
```



## Documentation

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-72/tr35.html#parts):

- Part 1: [Core](docs/Core.md) (languages, locales, basic structure)
- Part 2: [General](docs/General.md) (display names & transforms, etc.)
- Part 3: [Numbers](docs/Numbers.md) (number & currency formatting)
- Part 4: [Dates](docs/Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](docs/Supplemental.md) (supplemental data)



## Getting started

The CLDR is represented by a [Repository][] instance. The repository accesses data through a
[Provider][] instance. There are a few providers available in the package, as well as caching
mechanisms. Picking the right provider depends on your needs: you might want to favor flexibility
(during development) or predictability (in production).



### Favor flexibility

[WebProvider][] offers the maximum flexibility: when required, data is retrieved from the JSON
distribution [hosted on GitHub][2]. To avoid hitting the web with every request, it is recommended
to use a collection of caches, each with its own strategy. For example, [FileCache][] stores the
retrieved data as PHP files that can benefit from opcache.

The following example demonstrates how a repository can be instantiated:

```php
<?php

use ICanBoogie\CLDR\Cache\CacheCollection;
use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Cache\RedisCache;
use ICanBoogie\CLDR\Cache\RuntimeCache;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\WebProvider;
use ICanBoogie\CLDR\Repository;

/* @var \Redis $redis_client */

$provider = new CachedProvider(
    new WebProvider,
    new CacheCollection([
        new RunTimeCache,
        // You don't have to use Redis, this is only an example
        new RedisCache($redis_client),
        new FileCache(FileCache::RECOMMENDED_DIR)
    ])
);

$cldr = new Repository($provider);
```



### Favor predictability

You might want to favor predictability and restrict the usage of the repository to a few locales and
distribute them as part of your application on a read-only filesystem. You can warm up the CLDR
cache during development and commit the files, or as a building step of your CI/CD pipeline.

Use [the cldr command][] to warm up the CLDR cache:

```shell
./vendor/bin/cldr warm-up de en fr
```

The following example demonstrates how a repository can be instantiated with a restrictive provider
that uses warmed-up data only. With this configuration, the [FailingProvider][] throws an exception
if the data is not available in the cache.

```php
<?php

use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\FailingProvider;
use ICanBoogie\CLDR\Repository;

$provider = new CachedProvider(
    new FailingProvider(),
    new FileCache(FileCache::RECOMMENDED_DIR),
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

$english_locale = $repository->locale_for('en');
$french_locale = $repository->locale_for('fr');

$supplemental = $repository->supplemental;
# reading the default calendar
echo $supplemental['calendarPreferenceData']['001']; // gregorian
```



----------



## Continuous Integration

The project is continuously tested by [GitHub actions](https://github.com/ICanBoogie/CLDR/actions).

[![Tests](https://github.com/ICanBoogie/CLDR/actions/workflows/test.yml/badge.svg?branch=6.0)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Atest)
[![Static Analysis](https://github.com/ICanBoogie/CLDR/actions/workflows/static-analysis.yml/badge.svg?branch=6.0)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Astatic-analysis)



## Code of Conduct

This project adheres to a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in
this project and its community, you're expected to uphold this code.



## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for details.



## License

**icanboogie/cldr** is released under the [MIT License](LICENSE).



[ICanBoogie]:                   https://icanboogie.org/
[FileCache]:                    lib/Cache/FileCache.php
[Provider]:                     lib/Provider.php
[WebProvider]:                  lib/Provider/WebProvider.php
[FailingProvider]:              lib/Provider/FailingProvider.php
[Repository]:                   lib/Repository.php

[2]:                            https://github.com/unicode-cldr
[the cldr command]:             https://github.com/ICanBoogie/CLDR-CLI
