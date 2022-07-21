# General

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html)

## Units

Quantities of units such as years, months, days, hours, minutes and seconds can be formatted— for
example, in English, "1 day" or "3 days". It's easy to make use of this functionality via a locale's
units. [Many units are available](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#63-example-units).

```php
<?php

/* @var $cldr \ICanBoogie\CLDR\Repository */

$units = $cldr->locales['en']->units;

echo $units->duration_hour->name;                   // hours
echo $units->duration_hour->short_name;             // h
echo $units->duration_hour(1);                      // 1 hour
echo $units->duration_hour(23);                     // 23 hours
echo $units->duration_hour(23)->as_short;           // 23 hr
echo $units->duration_hour(23)->as_narrow;          // 23h
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#Unit_Elements)


### Compound units

Combination of units, such as _miles per hour_ or _liters per second_, can be created. Some units
already have 'precomputed' forms, such as `kilometer_per_hour`; where such units exist, they should
be used in preference.

```php
<?php

/* @var $cldr \ICanBoogie\CLDR\Repository */

$units = $cldr->locales['en']->units;

echo $units->volume_liter(12.345)->per($units->duration_hour);              // 12.345 liters per hour
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_short;    // 12.345 Lph
echo $units->volume_liter(12.345)->per($units->duration_hour)->as_narrow;   // 12.345l/h
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#compound-units)



### Unit Sequences (Mixed Units)

Units may be used in composed sequences, such as **5° 30m** for 5 degrees 30 minutes, or **3′ 2″**.
For that purpose, the appropriate width can be used to compose the units in a sequence.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$units = $repository->locales['en']->units;

$units->sequence
    ->angle_degree(5)
    ->duration_minute(30)
    ->as_long;
    // 5 degrees, 30 minutes

$units->sequence
    ->length_foot(3)
    ->length_inch(2)
    ->as_narrow;
    // 3′ 2″

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

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-general.html#Unit_Sequences)



## List formatting

Use the `format_list()` method of a locale to format a variable-length list of items:

```php
<?php

/* @var \ICanBoogie\CLDR\Repository $cldr */

# You can format a list in English
$en = $cldr->locales['en'];

echo $en->format_list([ "Monday" ]);
// Monday
echo $en->format_list([ "Monday", "Tuesday" ]);
// Monday and Tuesday
echo $en->format_list([ "Monday", "Tuesday", "Wednesday" ]);
// Monday, Tuesday, and Wednesday
echo $en->format_list([ "Monday", "Tuesday", "Friday", "Thursday" ]);
// Monday, Tuesday, Wednesday, and Thursday

# You can format a list in French
$fr = $cldr->locales['fr'];

echo $fr->format_list([ "lundi" ]);
// lundi
echo $fr->format_list([ "lundi", "mardi" ]);
// lundi et mardi
echo $fr->format_list([ "lundi", "mardi", "mercredi" ]);
// lundi, mardi et mercredi
echo $fr->format_list([ "lundi", "mardi", "mercredi", "jeudi" ]);
// lundi, mardi, jeudi et mercredi
```

Alternatively, you can get a list formatter using the `list_formatter` property:

```php
<?php

/* @var \ICanBoogie\CLDR\Repository $cldr */

$list_formatter = $cldr->locales['en']->list_formatter;

$list_formatter->format([ "Monday", "Tuesday", "Wednesday" ]);
// Monday, Tuesday, and Wednesday
```
