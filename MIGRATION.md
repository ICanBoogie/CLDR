# Migration

## v3.x to v4.x

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

### Deprecated Features

- The localized currency formatter no longer supports a `$symbols` parameter. If you need to
  customize how a currency is formatted, create your own `Symbols` instance and use it with a
  non-localized formatter e.g. `$repository->format_currency()`.

- The localized list formatter no longer accepts a list pattern or a type, only a type. If you
  need to customize how a list is formatted, create you own `ListPattern` instance and use it with
  a non-localized formatter e.g. `$repository->format_list()`.

### Other Changes

- Compatible with PHP 8.1+
- Targets [CLDR v36](http://cldr.unicode.org/index/downloads/cldr-36)
