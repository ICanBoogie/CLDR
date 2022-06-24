# Migration

## v3.x to v4.x

### New Requirements

Requires PHP 7.1+

### New features

None

### Backward Incompatible Changes

- [Numbers symbols](https://unicode.org/reports/tr35/tr35-numbers.html#Number_Symbols) are now
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

### Deprecated Features

- Removed the `$symbols` parameters formatter methods that use a localized formatter. If you need to
  customize how numbers are formatted, create your own `Symbols` instance and use it with a
  non-localized number/currency formatter.

### Other Changes

- Compatible with PHP 8.1+
- Targets [CLDR v36](http://cldr.unicode.org/index/downloads/cldr-36)
