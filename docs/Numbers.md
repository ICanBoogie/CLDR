# Numbers

This part covers [number elements](#number-elements), [number formatting](#number-formatting), and
[currencies](#currencies).

[Unicode Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html)

-----

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#parts):

- Part 1: [Core](Core.md) (languages, locales, basic structure)
- Part 2: [General](General.md) (display names & transforms, etc.)
- Part 3: [Numbers](Numbers.md) (number & currency formatting)
- Part 4: [Dates](Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](Supplemental.md) (supplemental data)

-----

<!-- ## Numbering Systems -->

## Number Elements

### Default Numbering System

This element indicates which numbering system should be used for presentation of numeric quantities
in the given locale.

```php
<?php

/**
 * @var ICanBoogie\CLDR\Locale $de 
 */

echo $de->numbers->default_numbering_system;
// latn
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#21-default-numbering-system)

<!-- ### Other Numbering Systems -->

### Number Symbols

Number symbols define the localized symbols that are commonly used when formatting numbers in a
given locale. They are represented by a `Symbols` entity. It's unlikely you'll have to use symbols
as long as you're using localized formatters.

```php
<?php

namespace ICanBoogie\CLDR;

/**
 * @var ICanBoogie\CLDR\Locale $en
 */

$en->numbers->symbols;
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

## Number formatting

You can format a number with a given pattern using the `format_number()` function of the repository:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository 
 */

echo $repository->format_number(4123.37, "#,#00.#0");
// 4,123.37
echo $repository->format_number(.3789, "#0.#0 %");
// 37.89 %
```

Alternatively, you format a number using a [NumberFormatter][] instance:

```php
<?php

use ICanBoogie\CLDR\NumberFormatter;

/**
 * @var ICanBoogie\CLDR\Repository $repository
 */

$number_formatter = $repository->number_formatter
# or
$number_formatter = new NumberFormatter();

echo $number_formatter(4123.37, "#,#00.#0");
// 4,123.37
echo $number_formatter(.3789, "#0.#0 %");
// 37.89 %
```



### Localized number formatting

Use the `format_number()` method of a [Locale][] to format a number using its conventions.

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $repository
 * @var ICanBoogie\CLDR\Locale $en
 * @var ICanBoogie\CLDR\Locale $fr
 */

echo $en->format_number(123456.78)
// 123,456.78

echo $fr->format_number(123456.78)
// 123 456,78
```

Alternatively, you can use a [LocalizedNumberFormatter][] instance:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Locale $en
 * @var ICanBoogie\CLDR\Locale $fr
 */

$localized_formatter = $en->number_formatter;
echo $localized_formatter->format(123456.78);
// 123,456.78

$localized_formatter = $fr->number_formatter;
echo $localized_formatter->format(123456.78);
// 123 456,78
```



## Currencies

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $cldr
 * @var ICanBoogie\CLDR\Territory $territory
 * @var ICanBoogie\CLDR\Locale $locale
 * @var string $locale_id
 */

$currency_code = 'EUR';

# You can obtain a currency from CLDR using its code
$currency = $cldr->currencies[$currency_code];

# You can obtain the main currency from a territory
$currency = $territory->currency;

# You can localize a currency, to get its local name, symbol, or format a number
$localized_currency = $currency->localize($locale_id);

echo $localized_currency->name;
// euro
echo $localized_currency->name_for(10);
// euros
echo $localized_currency->symbol;
// €
echo $localized_currency->format(12345.67);
// 12 345,67 €

# You can also format a currency directly from a locale
echo $locale->format_currency(12345.67, $currency_code);
// 12 345,67 €
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Currencies)



## Language Plural Rules

Languages have different pluralization rules for numbers that represent zero, one, tow, few, many or
other. ICanBoogie's CLDR makes it easy to find the plural rules for any numeric value:

```php
<?php

/**
 * @var ICanBoogie\CLDR\Repository $cldr 
 */

$cldr->plurals->rules_for('fr'); // [ 'one', 'other' ]
$cldr->plurals->rules_for('ar'); // [ 'zero', 'one', 'two', 'few', 'many', 'other' ]

$cldr->plurals->rule_for(1.5, 'fr'); // one
$cldr->plurals->rule_for(2, 'fr');   // other
$cldr->plurals->rule_for(2, 'ar');   // two
```

[Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#Language_Plural_Rules)

<!--

## Rule-Based Number Formatting

## Parsing Numbers

## Number Range Formatting

-->



[Locale]: ../lib/Locale.php
[LocalizedNumberFormatter]: ../lib/LocalizedNumberFormatter.php
[NumberFormatter]: ../lib/NumberFormatter.php
