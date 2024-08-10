# CLDR

[![Packagist](https://img.shields.io/packagist/v/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr)
[![Downloads](https://img.shields.io/packagist/dt/icanboogie/cldr.svg)](https://packagist.org/packages/icanboogie/cldr/stats)
[![Code Quality](https://img.shields.io/scrutinizer/quality/g/ICanBoogie/CLDR/6.0)](https://scrutinizer-ci.com/g/ICanBoogie/CLDR/?branch=6.0)

The __CLDR__ package facilitates the internationalization of your application by leveraging the data
and conventions established by the [Unicode Common Locale Data Repository][cldr] (CLDR). It provides
valuable locale information such as names for territories, languages, days… as well as formatters
for numbers, currencies, dates and times, units, sequences, and lists.

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
echo $fr->localized($fr)->name;                      // Français

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
$fr_datetime = new \ICanBoogie\CLDR\LocalizedDateTime($datetime, $fr);
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
$fr_euro = $euro->localized($fr);
echo $fr_euro->name;
echo $fr_euro->name_for(1);      // euro
echo $fr_euro->name_for(10);     // euros
echo $fr_euro->format(12345.67); // 12 345,67 €

# You can access territories and their localized data
$territory = $repository->territory_for('FR');
echo $territory;                                       // FR
echo $territory->currency;                             // EUR
echo $territory->currency_at('1977-06-06');            // FRF
echo $territory->currency_at('now');                   // EUR
echo $territory->name_as('fr');        // France
echo $territory->name_as('it');        // Francia
echo $territory->name_as('ja');        // フランス
echo $repository->territory_for('FR')->first_day;        // mon
echo $repository->territory_for('EG')->first_day;        // sat
echo $repository->territory_for('BS')->first_day;        // sun
echo $repository->territory_for('AE')->weekend_start;    // fri
echo $repository->territory_for('AE')->weekend_end;      // sat
echo $territory->localized('fr')->name; // France
echo $territory->localized('it')->name; // Francia
echo $territory->localized('ja')->name; // フランス
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

The [CLDR][cldr] is represented by a [Repository][] instance, which accesses data through a
[Provider][] instance. The package offers several and caching mechanisms. Choosing the right
provider depends on your requirements: you may prioritize flexibility during development or
predictability in production.



### Favor flexibility

[WebProvider][] offers maximum flexibility by retrieving data from the JSON distribution [hosted on
GitHub][2] as needed. To minimize web requests, it is advised to use a collection of caches, each
with its own strategy. For example, [FileCache][] stores the retrieved data as PHP files, allowing
it to benefit from opcache.

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

For greater predictability, consider limiting the repository's usage to a few specific locales and
distributing them as part of your application. You can prepopulate the CLDR cache during development
and commit the files, or incorporate this step into your CI/CD pipeline build process.

Use [the cldr command][] to warm up the CLDR cache:

```shell
./vendor/bin/cldr warm-up de en fr
```

The following example illustrates a setup to limit data access to the cache. [RestrictedProvider][]
throws an exception in an attempt to retrieve data not available in the cache.

```php
<?php

use ICanBoogie\CLDR\Cache\FileCache;
use ICanBoogie\CLDR\Provider\CachedProvider;
use ICanBoogie\CLDR\Provider\RestrictedProvider;
use ICanBoogie\CLDR\Repository;

$provider = new CachedProvider(
    new RestrictedProvider(),
    new FileCache(FileCache::RECOMMENDED_DIR),
);

$cldr = new Repository($provider);
```



----------



## Continuous Integration

The project is continuously tested by [GitHub actions](https://github.com/ICanBoogie/CLDR/actions).

[![Tests](https://img.shields.io/github/actions/workflow/status/ICanBoogie/CLDR/test.yml?branch=6.0&label=tests)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Atest)
[![Code Coverage](https://coveralls.io/repos/github/ICanBoogie/CLDR/badge.svg?branch=6.0)](https://coveralls.io/r/ICanBoogie/CLDR)
[![Static Analysis](https://img.shields.io/github/actions/workflow/status/ICanBoogie/CLDR/static-analysis.yml?branch=6.0&label=static-analysis)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Astatic-analysis)
[![Code Style](https://img.shields.io/github/actions/workflow/status/ICanBoogie/CLDR/code-style.yml?branch=6.0&label=code-style)](https://github.com/ICanBoogie/CLDR/actions?query=workflow%3Acode-style)



## Code of Conduct

This project adheres to a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). By participating in
this project and its community, you're expected to uphold this code.



## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for details.



## License

**icanboogie/cldr** is released under the [MIT License](LICENSE).



[ICanBoogie]:                   https://icanboogie.org/
[FileCache]:                    src/Cache/FileCache.php
[Provider]:                     src/Provider.php
[WebProvider]:                  src/Provider/WebProvider.php
[RestrictedProvider]:           src/Provider/RestrictedProvider.php
[Repository]:                   src/Repository.php

[2]:                            https://github.com/unicode-cldr
[cldr]:                         http://cldr.unicode.org/
[the cldr command]:             https://github.com/ICanBoogie/CLDR-CLI
