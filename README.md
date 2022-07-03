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
> The package targets [CLDR version 41](https://www.unicode.org/reports/tr35/tr35-66/tr35.html).



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

- Part 1: Core (languages, locales, basic structure)
- Part 2: General (display names & transforms, etc.)
- Part 3: Numbers (number & currency formatting)
- Part 4: Dates (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](docs/Supplemental.md) (supplemental data)



## Repository

The CLDR is represented by a [Repository][] instance, from which data is accessed. When required,
data is retrieved through a provider. The _web_ provider fetches data from the JSON distribution
[hosted on GitHub][2]. In order to avoid hitting the web with every request, a collection of caches
is used, each with its own strategy.

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

$repository = new Repository($provider);
```





### Accessing the repository

The repository can be accessed like a big array, but it also provides interfaces to the most
important data such as locales, territories, numbers, currencies…

The following example demonstrates how the repository can be used to access locales and
supplemental data:

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$english_locale = $repository->locales['en'];
$french_locale = $repository->locales['fr'];

$repository->available_locales;            // [ … 'en', …, 'fr', … ];
$repository->is_locale_available('fr');    // true
$repository->is_locale_available('fr-FR'); // false

$supplemental = $repository->supplemental;
# reading the default calendar
echo $supplemental['calendarPreferenceData']['001']; // gregorian
```





## Locales

The data and conventions of a locale are represented by a [Locale][] instance, which can be used
as an array to access various raw data such as calendars, characters, currencies, delimiters,
languages, territories and more.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$locale = $repository->locales['fr'];

echo $locale['characters']['auxiliary'];      // [á å ä ã ā ē í ì ī ñ ó ò ö ø ú ǔ]
echo $locale['delimiters']['quotationStart']; // «
echo $locale['territories']['TF'];            // Terres australes françaises
```

Locales provide a collection of calendars, and the `calendar` property is often used to
obtain the default calendar of a locale.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$locale = $repository->locales['fr'];

echo $locale['ca-gregorian']['days']['format']['wide']['sun'];         // dimanche
# or using the calendar collection
echo $locale->calendars['gregorian']['days']['format']['wide']['sun']; // dimanche
# or because 'gregorian' is the default calendar for this locale
echo $locale->calendar['days']['format']['wide']['sun'];               // dimanche
```





### Localized objects

Locales are also often used to localize instances such as [Currency][], [Territory][], or even
[Locale][]. The method `localize` is used to localize instances. The method
tries its best to find a suitable _localizer_, and it helps if the instance to localize implements
[Localizable][], or if a `ICanBoogie\CLDR\Localized<class_base_name>` class is defined.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$datetime = new \DateTime;
$localized_datetime = $repository->locales['fr']->localize($datetime);
echo get_class($localized_datetime); // ICanBoogie\CLDR\LocalizedDateTime
```

Instances that can be localized usually implement the `localize()` method.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

echo $repository->territories['FR']->localize('fr')->name; // France
```





### Localized locales

A localized locale can be obtained with the `localize()` method, or the `localize()` method
of the desired locale.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$locale = $repository->locales['fr'];

echo $locale->localize('fr')->name;                         // Français
# or
echo $repository->locales['fr']->localize($locale)->name;   // Français
```





### Context transforms

Several capitalization contexts can be distinguished for which different languages use different
capitalization behavior for dates, date elements, names of languages/regions/currencies. The
`context_transform()` method helps capitalizing these elements:

```php
<?php

use ICanBoogie\CLDR\ContextTransforms;

/* @var $repository \ICanBoogie\CLDR\Repository */

echo $repository->locales['fr']->context_transform(
	"juin",
	ContextTransforms::USAGE_MONTH_FORMAT_EXCEPT_NARROW,
	ContextTransforms::TYPE_STAND_ALONE
);

// Juin
```




## Calendars

Calendars are represented by a [Calendar][] instance, they can be accessed as arrays, and also
provide magic properties to rapidly access days, eras, months and quarters:

```php
<?php

use ICanBoogie\CLDR\Calendar;

/* @var $repository \ICanBoogie\CLDR\Repository */

$calendar = new Calendar($repository->locales['fr'], $repository->locales['fr']['ca-gregorian']);
# or
$calendar = $repository->locales['fr']->calendars['gregorian'];
# or
$calendar = $repository->locales['fr']->calendar; // because "gregorian" is the default calendar for this locale

$calendar->standalone_abbreviated_days;
# or $calender['days']['stand-alone']['abbreviated'];

$calendar->abbreviated_days;
# or $calender['days']['format']['abbreviated'];
```

This works with days, eras, months, quarters and the following widths: `abbreviated`, `narrow`,
`short`, and `wide`. Here are some examples:

```php
<?php

/* @var $calendar \ICanBoogie\CLDR\Calendar */

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

/* @var $repository \ICanBoogie\CLDR\Repository */

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

/* @var $repository \ICanBoogie\CLDR\Repository */

$formatter = new DateTimeFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->datetime_formatter;

$datetime = '2013-11-02 22:23:45 UTC';

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

/* @var $repository \ICanBoogie\CLDR\Repository */

$formatter = new DateFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->date_formatter;

$datetime = '2013-11-05 21:22:23';

echo $formatter($datetime, $formatter::WIDTH_FULL);   // Tuesday, November 5, 2013
echo $formatter($datetime, $formatter::WIDTH_LONG);   // November 5, 2013
echo $formatter($datetime, $formatter::WIDTH_MEDIUM); // Nov 5, 2013
echo $formatter($datetime, $formatter::WIDTH_SHORT);  // 11/5/13
```





### Time formatter

Calendars provide a formatter for times. A width or a pattern is used for the formatting.

```php
<?php

use ICanBoogie\CLDR\TimeFormatter;

/* @var $repository \ICanBoogie\CLDR\Repository */

$formatter = new TimeFormatter($repository->locales['en']->calendar);
# or
$formatter = $repository->locales['en']->calendar->time_formatter;

$datetime = '2013-11-05 21:22:23 UTC';

echo $formatter($datetime, $formatter::WIDTH_FULL);   // 9:22:23 PM UTC
echo $formatter($datetime, $formatter::WIDTH_LONG);   // 9:22:23 PM UTC
echo $formatter($datetime, $formatter::WIDTH_MEDIUM); // 9:22:23 PM
echo $formatter($datetime, $formatter::WIDTH_SHORT);  // 9:22 PM
```





### Localized DateTime

`DateTime` can be localized by wrapping them inside a [LocalizedDateTime][] instance, or by using
the `localize` method of the desired locale:

```php
<?php

use ICanBoogie\CLDR\LocalizedDateTime;

/* @var $repository \ICanBoogie\CLDR\Repository */

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





## Number formatting

[NumberFormatter][] can be used to format numbers.

```php
<?php

use ICanBoogie\CLDR\NumberFormatter;

$formatter = new NumberFormatter;
$formatter(4123.37, "#,#00.#0");
// 4,123.37
$formatter(.3789, "#0.#0 %");
// 37.89 %
```

> **Note:** You can also obtain a number formatter, or format a number from the repository.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$number_formatter = $repository->number_formatter;

echo $repository->format_number(4123.37, "#,#00.#0"); // 4,123.37
```






## Localized number formatting

A localized number formatter can be obtained with the `localize()` method (if the instance was
created with a repository), or the `localize()` method of the desired locale. By default, the
list is formatted with the _standard_ type, but you can also provide your own pattern.

```php
<?php

use ICanBoogie\CLDR\NumberFormatter;
use ICanBoogie\CLDR\LocalizedNumberFormatter;

/* @var $repository \ICanBoogie\CLDR\Repository */

$formatter = new NumberFormatter($repository);

$localized_formatter = $repository->locales['fr']->localize($formatter);
# or
$localized_formatter = new LocalizedNumberFormatter($formatter, $repository->locales['fr']);

$localized_formatter(123456.78);
// 123 456,78
$repository->locales['en']->localize($formatter)->format(123456.78);
// 123,456.78
```

> **Note:** You can also obtain a localized number formatter, or format a number from a locale.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$localized_number_formatter = $repository->locales['fr']->number_formatter;
echo $repository->locales['fr']->format_number(123456.78);
```








## List formatting

[ListFormatter][] can be used to format variable-length lists of things such as
"Monday, Tuesday, Friday, and Saturday".

```php
<?php

namespace ICanBoogie\CLDR;

$list_patterns = Locale\ListPattern::from([

	'start' => "{0}, {1}",
	'middle' => "{0}, {1}",
	'end' => "{0}, and {1}",
	'2' =>  "{0} and {1}"

]);

$formatter = new ListFormatter;

$formatter([ "Monday" ], $list_patterns);
// Monday
$formatter([ "Monday", "Tuesday" ], $list_patterns);
// Monday and Tuesday
$formatter([ "Monday", "Tuesday", "Friday" ], $list_patterns);
// Monday, Tuesday, and Friday
$formatter([ "Monday", "Tuesday", "Friday", "Saturday" ], $list_patterns);
// Monday, Tuesday, Friday, and Saturday
```

> **Note:** You can also obtain a list formatter, or format a list from the repository.

```php
<?php

namespace ICanBoogie\CLDR;

/* @var $repository Repository */

$list_patterns = Locale\ListPattern::from([

	'2' =>  "{0} and {1}",
	'start' => "{0}, {1}",
	'middle' => "{0}, {1}",
	'end' => "{0}, and {1}",

]);

$list_formatter = $repository->list_formatter;
echo $repository->format_list([ "Monday", "Tuesday", "Friday" ], $list_patterns);
```






### Localized list formatting

A localized list formatter can be obtained with the `localize()` method (if the instance was
created with a repository), or the `localize()` method of the desired locale. By default, the
list is formatted with the "standard" type, but more types are available, and you can also
provide your own list patterns.

```php
<?php

namespace ICanBoogie\CLDR;

/* @var $repository \ICanBoogie\CLDR\Repository */

$formatter = new ListFormatter($repository);

$localized_formatter = $repository->locales['fr']->localize($formatter);
# or
$localized_formatter = new LocalizedListFormatter($formatter, $repository->locales['fr']);

$localized_formatter([ "lundi", "mardi", "vendredi", "samedi" ]);
# or
$localized_formatter([ "lundi", "mardi", "vendredi", "samedi" ], 'standard');
# or
$localized_formatter([ "lundi", "mardi", "vendredi", "samedi" ], LocalizedListFormatter::TYPE_STANDARD);
// lundi, mardi, vendredi et samedi
```

> **Note:** You can also obtain a localized list formatter, or format a list from a locale.

```php
<?php

namespace ICanBoogie\CLDR;

/* @var $repository Repository */

$localized_list_formatter = $repository->locales['fr']->list_formatter;
echo $repository->locales['fr']->format_list([ "Monday", "Tuesday", "Friday" ]);
```





## Units

Quantities of units such as years, months, days, hours, minutes and seconds can be formatted— for
example, in English, "1 day" or "3 days". It's easy to make use of this functionality via a locale's
units:

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;
echo $units->duration_hour->name;                   // hours
echo $units->duration_hour->short_name;             // h
echo $units->duration_hour(1);                      // 1 hour
echo $units->duration_hour(23);                     // 23 hours
echo $units->duration_hour(23)->as_short;           // 23 hr
echo $units->duration_hour(23)->as_narrow;          // 23h
```

[Many units are available](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#Unit_Elements).

### Per unit

Combination of units, such as _miles per hour_ or _liters per second_, can be created. Some units
already have 'precomputed' forms, such as `kilometer_per_hour`; where such units exist, they should
be used in preference.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;
echo $units->volume_liter(12.345)->per($units->duration_hour);              // 12.345 liters per hour
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_short;    // 12.345 Lph
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_narrow;   // 12.345l/h
```



### Units in composed sequence

Units may be used in composed sequences, such as **5° 30m** for 5 degrees 30 minutes, or **3 ft, 2
in**. For that purpose, the appropriate width can be used to compose the units in a sequence.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;

$units->sequence
	->angle_degree(5)
	->duration_minute(30)
	->as_narrow;
	// 5° 30m

$units->sequence
	->length_foot(3)
	->length_inch(2)
	->as_short;
	// 3 ft, 2 in

$units = $repository->locales['fr']->units;

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_long;
	// 12 heures, 34 minutes et 56 secondes

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_short;
	// 12 h, 34 min et 56 s

$units->sequence
	->duration_hour(12)
	->duration_minute(34)
	->duration_second(45)
	->as_narrow;
	// 12h 34m 56s
```





## Plurals

Languages have different pluralization rules for numbers that represent zero, one, tow, few, many or
other. ICanBoogie's CLDR makes it easy to find the plural rules for any numeric value:

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$repository->plurals->rules_for('fr'); // [ 'one', 'other' ]
$repository->plurals->rules_for('ar'); // [ 'zero', 'one', 'two', 'few', 'many', 'other' ]

$repository->plurals->rule_for(1.5, 'fr'); // one
$repository->plurals->rule_for(2, 'fr');   // other
$repository->plurals->rule_for(2, 'ar');   // two
```










## Territories

The information about a territory is represented by a [Territory][] instance, which aggregates
information that is actually scattered across the CLDR.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

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

/* @var $repository \ICanBoogie\CLDR\Repository */

$territory = $repository->territories['FR'];

$localized_territory = $territory->localize('fr');
# or
$localized_territory = $repository->locales['fr']->localize($territory);

echo $territory->localize('fr')->name;   // France
echo $territory->localize('it')->name;   // Francia
echo $territory->localize('ja')->name;   // フランス
```





## Currencies

Currencies are represented by instances of [Currency][]. You can create the instance yourself or
get one through the currency collection.

```php
<?php

use ICanBoogie\CLDR\Currency;

/* @var $repository \ICanBoogie\CLDR\Repository */

$euro = new Currency($repository, 'EUR');
# or
$euro = $repository->currencies['EUR'];
```





### Localized currencies

A localized currency can be obtained with the `localize()` method, or the `localize()` method
of the desired locale, it is often used to format a currency using the convention of a locale.

```php
<?php

use ICanBoogie\CLDR\Currency;

/* @var $repository \ICanBoogie\CLDR\Repository */

$currency = new Currency($repository, 'EUR');

$localized_currency = $currency->localize('fr');
# or
$localized_currency = $repository->locales['fr']->localize($currency);

echo $localized_currency->name;             // euro
echo $localized_currency->name(1);          // euro
echo $localized_currency->name(10);         // euros
echo $localized_currency->format(12345.67); // 12 345,67 €
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
