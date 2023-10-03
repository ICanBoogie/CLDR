# Migration

## v4.x to v5.0

### New Requirements

- PHP >=7.1 <8.2

### New features

- Support for the new [plural operand](https://www.unicode.org/reports/tr35/tr35-66/tr35-numbers.html#table-plural-operand-meanings) `e` and compact decimal exponent e.g. `123c6`.

- The optional parameter `data_path` to `Repository::fetch()` can be used by the function to burrow in the data to fetch a target.

- `CurrencyCollection::$codes` returns the list of defined currency codes.

- Support for Supplemental Currency Data Fractions:

	```php
	<?php

	/* @var $cldr \ICanBoogie\CLDR\Repository */

	$euro_fraction = $cldr->supplemental->currency_data->fraction_for('EUR');

	echo $euro_fraction->digits;        // 2
	echo $euro_fraction->rounding;      // 0
	echo $euro_fraction->cash_digits;   // 2
	echo $euro_fraction->cash_rounding; // 0
	```

### Backward Incompatible Changes

- `PathMapper` was replaced by `GitHub\UrlResolver`.

- With the advent of `Fraction`, the properties `digits`, `rounding`, `cash_digits`, and `cash_rounding` have been removed from `Currency`. Get them through the `fraction` property:

	```php
	<?php

	/* @var \ICanBoogie\CLDR\Currency $currency */

	$currency->digits;
	```

	```php
	<?php

	/* @var \ICanBoogie\CLDR\Currency $currency */

	$currency->fraction->digits;
	```

- Renamed `Units::format_combination()` as `format_compound()` to [match the language used by Unicode](http://unicode.org/reports/tr35/tr35-general.html#compound-units).

### Deprecated Features

None

### Other Changes

- Targets [CLDR v41](https://www.unicode.org/reports/tr35/tr35-66/tr35.html)

- As of CLDR v38, all JSON data is contained in this single repository. References and the way they are resolved internal has been updated, without breaking the public interfaces.

- Improved documentation and moved elements from the README to the [docs](docs) directory.




## v3.x to v4.0

### New Requirements

Requires PHP 7.1+

### New features

None

### Backward Incompatible Changes

- [Numbers symbols](https://www.unicode.org/reports/tr35/tr35-57/tr35-numbers.html#Number_Symbols) are now
  represented by a `Symbols` instance instead of an array. Methods using numeric symbols have been
  updated. The currency symbol is no longer added to the numeric symbols, it is now a separated
  parameter.

	```php
	<?php

	/* @var ICanBoogie\CLDR\CurrencyFormatter $formatter */
	/* @var array $symbols */

	$formatter->format($number, $pattern, $symbols);
	```

	```php
	<?php

	/* @var ICanBoogie\CLDR\CurrencyFormatter $formatter */
	/* @var ICanBoogie\CLDR\Numbers\Symbols $symbols */
	/* @var string $currencySymbol */

	$formatter->format($number, $pattern, $symbols, $currencySymbol);
	```

- [List patterns](https://www.unicode.org/reports/tr35/tr35-57/tr35-general.html#ListPatterns) are
  now represented by a `ListPattern` instance instead of an array. Methods using a list pattern have
  been updated.

	```php
	<?php

	/* @var ICanBoogie\CLDR\ListFormatter $formatter */
	/* @var array $list_pattern */

	$formatter->format([ 1, 2, 3 ], list_pattern);
	```

	```php
	<?php

	/* @var ICanBoogie\CLDR\ListFormatter $formatter */
	/* @var ICanBoogie\CLDR\Locale\ListPattern $list_pattern */

	$formatter->format([ 1, 2, 3 ], list_pattern);
	```

- Removed `NumberPattern:$format`, it was never used.

- Removed the `localized()` method on entities that don't naturally require access to the
  repository: `NumberFormatter` and `ListFormatter`. You can use
  `$repository->locales['fr']->localize($formatter)` to get a localized formatter, or the
  `number_formatter` and `list_formater` properties of the `Locale` object.

- The fluent interface of units is now more on par with the rest of the API.

	```php
	<?php

	echo $units->duration_hour(23);
	echo $units->duration_hour(23, $units::LENGTH_SHORT);
	echo $units->volume_liter->per_unit(12.345, $units->duration_hour);
	echo $units->volume_liter->per_unit(12.345, $units->duration_hour, $units::LENGTH_SHORT);
	```

	```php
	<?php

	echo $units->duration_hour(23);
	echo $units->duration_hour(23)->as_short;
	echo $units->volume_liter(12.345)->per($units->duration_hour);
	echo $units->volume_liter(12.345)->per($units->duration_hour)->as_short;
	```

### Deprecated Features

- The localized currency formatter no longer supports a `$symbols` parameter. If you need to
  customize how a currency is formatted, create your own `Symbols` instance and use it with a
  non-localized formatter e.g. `$repository->format_currency()`.

- The localized list formatter no longer accepts a list pattern or a type, only a type. If you
  need to customize how a list is formatted, create you own `ListPattern` instance and use it with
  a non-localized formatter e.g. `$repository->format_list()`.

### Other Changes

- Compatible with PHP 8.1+
- Targets [CLDR v36](https://www.unicode.org/reports/tr35/tr35-57/tr35.html)
- Improved type annotations, including generics.
