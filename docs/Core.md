# Core

This part covers [locales](#locales).

[Unicode Reference](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#Contents)

-----

The documentation is divided into the following parts, mimicking [Unicode's documentation](https://www.unicode.org/reports/tr35/tr35-66/tr35.html#parts):

- Part 1: [Core](Core.md) (languages, locales, basic structure)
- Part 2: [General](General.md) (display names & transforms, etc.)
- Part 3: [Numbers](Numbers.md) (number & currency formatting)
- Part 4: [Dates](Dates.md) (date, time, time zone formatting)
- Part 5: Collation (sorting, searching, grouping)
- Part 6: [Supplemental](Supplemental.md) (supplemental data)

-----



## Locales

The data and conventions of a locale are represented by a [Locale][] instance, which can be used as
an array to access various raw data such as calendars, characters, currencies, delimiters,
languages, territories and more.

```php
<?php

/* @var $repository \ICanBoogie\CLDR\Repository */

$locale = $repository->locales['fr'];

echo $locale['characters']['auxiliary'];      // [á å ä ã ā ē í ì ī ñ ó ò ö ø ú ǔ]
echo $locale['delimiters']['quotationStart']; // «
echo $locale['territories']['TF'];            // Terres australes françaises
```

Locales provide a collection of calendars, and the `calendar` property is often used to obtain the
default calendar of a locale.

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
[Locale][]. The method `localize` is used to localize instances. The method tries its best to find a
suitable _localizer_, and it helps if the instance to localize implements [Localizable][], or if a
`ICanBoogie\CLDR\Localized<class_base_name>` class is defined.

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

A localized locale can be obtained with the `localize()` method, or the `localize()` method of the
desired locale.

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
`context_transform()` method helps to capitalize these elements:

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

[Currency]: ../lib/Currency.php
[Locale]: ../lib/Locale.php
[Localizable]: ../lib/Localizable.php
[Territory]: ../lib/Territory.php
