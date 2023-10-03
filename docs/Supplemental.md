# Supplemental

This part covers information that is important for internationalization and proper use of CLDR, but
is not contained in the locale hierarchy. It is not localizable, nor is it overridden by locale
data.

[Unicode Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-info.html#Contents)

-----

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#parts):

- Part 1: [Core](Core.md) (languages, locales, basic structure)
- Part 2: [General](General.md) (display names & transforms, etc.)
- Part 3: [Numbers](Numbers.md) (number & currency formatting)
- Part 4: [Dates](Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](Supplemental.md) (supplemental data)

-----



## Territories

The information about a territory is represented by a [Territory][] instance, which aggregates
information that is actually scattered across the CLDR.

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository 
 */

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

/**
 * @var ICanBoogie\CLDR\Repository $repository 
 */

$territory = $repository->territories['FR'];

$localized_territory = $territory->localize('fr');
# or
$localized_territory = $repository->locales['fr']->localize($territory);

echo $territory->localize('fr')->name;   // France
echo $territory->localize('it')->name;   // Francia
echo $territory->localize('ja')->name;   // フランス
```

## Supplemental Currency Data

Use `fraction_for()` to get the fraction information for a currency:

```php
<?php

/* @var $cldr \ICanBoogie\CLDR\Repository */

$euro_fraction = $cldr->supplemental->currency_data->fraction_for('EUR');

echo $euro_fraction->digits;        // 2
echo $euro_fraction->rounding;      // 0
echo $euro_fraction->cash_digits;   // 2
echo $euro_fraction->cash_rounding; // 0
```



[Territory]: ../lib/Territory.php
