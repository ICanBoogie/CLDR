# Numbers

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html)

<!-- ## Numbering Systems -->

## Number Elements

<!--

### Default Numbering System

### Other Numbering Systems

-->

### Number Symbols

Number symbols define the localized symbols that are commonly used when formatting numbers in a
given locale. They are represented by a `Symbols` entity. It's unlikely you'll have to use symbols
as long as you're using localized formatters.

```php
<?php

namespace ICanBoogie\CLDR;

/**
 * @var Repository $cldr
 * @var string $locale_id
 */

$cldr->locales[$locale_id]->numbers->symbols;
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Numbering_Systems)

<!--

### Number Formats

#### Compact Number Formats

#### Currency Formats

### Miscellaneous Patterns

### Minimal Pairs

## Number Format Patterns

-->

## Currencies

```php
<?php

namespace ICanBoogie\CLDR;

/**
 * @var Repository $cldr
 * @var Territory $territory
 * @var Locale $locale
 * @var string $locale_id
 */

$currency_code = 'EUR';

# You can obtain a currency from CLDR using its code
$currency = $cldr->currencies[$currency_code];

# You can obtain the main currency from a territory
$currency = $territory->currency;

# You can localize a currency, to get its local name, symbol, or format a number
$localized_currency = $currency->localize($locale_id);

echo $localized_currency->name;                          // euro
echo $localized_currency->name_for(10);                  // euros
echo $localized_currency->symbol;                        // €
echo $localized_currency->format(12345.67);              // 12 345,67 €

# You can also format a currency directly from a locale
echo $locale->format_currency(12345.67, $currency_code); // 12 345,67 €
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Currencies)

<!--

## Language Plural Rules

## Rule-Based Number Formatting

## Parsing Numbers

## Number Range Formatting

-->
